<?php

namespace Gnutix\Library\Loader;

use Symfony\Component\Yaml\Yaml;

use Gnutix\Library\LoaderInterface;

/**
 * YAML File Loader
 */
class YamlFileLoader implements LoaderInterface
{
    /** @var string */
    protected $filePath;

    /** @var array */
    protected $data;

    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException If the file path does not exists
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException('The file "'.$filePath.'" has not been found.');
        }

        $this->data = (array) Yaml::parse(file_get_contents($filePath), true, true);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getSourceFilePath()
    {
        return $this->filePath;
    }
}
