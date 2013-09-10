<?php

namespace Commands;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Base Command
 *
 * @method \AppConsole getApplication()
 */
abstract class Command extends BaseCommand
{
    /** @var \Symfony\Component\Console\Input\InputInterface */
    protected $input;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $output;

    /** @var \Symfony\Component\Console\Helper\FormatterHelper */
    protected $formatter;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input  The input instance
     * @param \Symfony\Component\Console\Output\OutputInterface $output The output instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->formatter = $this->getHelper('formatter');
    }

    /**
     * Output a command's title
     *
     * @param string $message    Message
     * @param string $styles     Styles
     * @param bool   $large      Large block or not
     * @param bool   $breakAfter Break line after the block
     */
    protected function outputBlock($message, $styles = 'question', $large = true, $breakAfter = true)
    {
        $this->output->writeln($this->formatter->formatBlock($message, $styles, $large));
        if ($breakAfter) {
            $this->output->write(PHP_EOL);
        }
    }

    /**
     * Output an info
     *
     * @param string $section     Section of the message
     * @param string $message     Message
     * @param string $styles      Styles
     * @param bool   $breakBefore Break line before the section
     * @param bool   $breakAfter  Break line after the section
     */
    protected function outputSection($section, $message, $styles = 'comment', $breakBefore = false, $breakAfter = false)
    {
        if ($breakBefore) {
            $this->output->write(PHP_EOL);
        }
        $this->output->write($this->formatter->formatSection($section, $message, $styles));
        if ($breakAfter) {
            $this->output->write(PHP_EOL);
        }
    }

    /**
     * @param string $command         Command to run
     * @param bool   $liveOutput      Define if we display the output of the command in live
     * @param int    $timeout         Timeout for the command to fail
     * @param bool   $throwExceptions Define if we want the method to throw exceptions
     * @param bool   $oneLiner        Puts the command on one line
     *
     * @return \Symfony\Component\Process\Process
     * @throws \RuntimeException
     * @internal This method must remain public as it's used in closures.
     */
    public function runProcess(
        $command,
        $liveOutput = false,
        $timeout = 120,
        $throwExceptions = true,
        $oneLiner = false
    ) {
        if ($oneLiner) {
            $command = preg_replace('/\s+/', ' ', $command);
        }

        // Instantiate a process object
        $process = new Process($command);
        $process->setTimeout($timeout);
        $process->run($liveOutput ? array($this, 'outputProcess') : null);

        // Check the process result
        if ($throwExceptions && !$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput().' // '.$process->getOutput());
        }

        // Return the console output of the process
        return $process;
    }

    /**
     * Output the process's output in live time
     *
     * @param string $type   Type of the output
     * @param string $buffer Output string
     *
     * @internal Warning ! This class is used by runProcess() and must remain public in order to work
     */
    public function outputProcess($type = '', $buffer = '')
    {
        if (false === strpos($buffer, 'stdin')) {
            $this->output->write((('err' === $type) ? 'WARNING: ' : '').$buffer);
        }
    }
}
