<?php

use Symfony\Component\Console\Application;

use Commands\CodingStandardCommand;

/**
 * Application Console
 */
class AppConsole extends Application
{
    /** @var string */
    protected $rootDir;

    /**
     * {@inheritDoc}
     */
    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            array(
                new CodingStandardCommand(),
            )
        );
    }

    /**
     * @return string
     */
    public function getApplicationRootDir()
    {
        return realpath($this->getRootDir().'/..');
    }

    /**
     * @return string
     *
     * @see \Symfony\Component\HttpKernel\Kernel::getRootDir()
     * @author Fabien Potencier <fabien@symfony.com>
     */
    private function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->rootDir;
    }
}
