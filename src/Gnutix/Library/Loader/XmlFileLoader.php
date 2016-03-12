<?php

namespace Gnutix\Library\Loader;

use Gnutix\Library\LoaderInterface;

/**
 * XML File Loader
 */
class XmlFileLoader implements LoaderInterface
{
    /** @var string */
    protected $filePath;

    /** @var \SimpleXMLElement */
    protected $data;

    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException If the file path does not exists
     * @throws \UnexpectedValueException If the XML file can't be parsed
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException('The file "'.$filePath.'" has not been found.');
        }

        if (false === ($this->data = simplexml_load_file($filePath))) {
            throw new \UnexpectedValueException('Unable to parse the XML file "'.$filePath.'".');
        }
    }

    /**
     * @return \SimpleXMLElement
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
