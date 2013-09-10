<?php

namespace Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Coding Standard Command
 */
class CodingStandardCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('cs')
            ->setDescription('Run coding standard inspection tools.')
            ->addOption(
                'skip',
                null,
                InputOption::VALUE_REQUIRED,
                'Skip some inspectors (input as a CSV list). '.
                'Could be any of the following value: '.implode(', ', array_keys($this->getInspectors())).'.'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inspectorsCallbacks = $this->getInspectors();
        $skippedInspectors = explode(',', $this->input->getOption('skip'));

        $exitCodes = array();
        foreach ($inspectorsCallbacks as $id => $callback) {
            if (in_array($id, $skippedInspectors, true)) {
                if (OutputInterface::VERBOSITY_DEBUG <= $this->input->getOption('verbose')) {
                    $this->outputSection('Debug', 'Inspector "'.$id.'" has been skipped.', 'comment', false, true);
                }
                continue;
            }

            $process = call_user_func($callback);
            if (!($process instanceof Process)) {
                throw new \UnexpectedValueException('The return value of an inspector callback must be a Process.');
            }

            $exitCodes[] = $process->getExitCode();
        }

        if (!empty($exitCodes) && array(0) !== array_unique($exitCodes)) {
            throw new \UnexpectedValueException('There are validation errors in the code. Please, fix them.');
        }
    }

    /**
     * @return array
     */
    protected function getInspectors()
    {
        return array(
            'phpcs' => array($this, 'runPhpCS'),
            'jshint' => array($this, 'runJsHint'),
        );
    }

    /**
     * Runs PHPCS inspection tool
     */
    protected function runPhpCS()
    {
        $root = $this->getApplication()->getApplicationRootDir();
        $phpcsExecutable = $root.'/bin/phpcs';

        // Ensure the binaries needed are available
        if (!file_exists($phpcsExecutable)) {
            throw new \UnexpectedValueException(
                'The file "'.$phpcsExecutable.'" could not be found. Have you installed the project\'s vendors ?'
            );
        }

        // Run PHPCS
        $this->outputBlock('PHP Code Sniffer validation');

        return $this->runProcess(
            $phpcsExecutable.' '.$root.'/src --extensions=php --standard=PSR2 -p',
            true,
            60 * 5,
            false
        );
    }

    /**
     * Runs jsHint inspection tool
     */
    protected function runJsHint()
    {
        $root = $this->getApplication()->getApplicationRootDir();

        $this->outputBlock('JS Hint validation');
        $jsHint = $this->runProcess(
            'jshint . --config="'.$root.'/.jshintrc" --verbose',
            true,
            60,
            false
        );

        // Add an output for happy jsHint
        if (null === $jsHint->getOutput()) {
            $this->output->writeln('No error reported.');
        }

        return $jsHint;
    }
}
