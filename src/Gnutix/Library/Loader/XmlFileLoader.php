<?php

declare(strict_types=1);

namespace Gnutix\Library\Loader;

use Gnutix\Library\LoaderInterface;
use SimpleXMLElement;
use Webmozart\Assert\Assert;

final class XmlFileLoader implements LoaderInterface
{
    private string $filePath;
    private SimpleXMLElement $data;

    public function __construct(string $filePath)
    {
        Assert::fileExists($filePath);
        $this->filePath = $filePath;

        $fileContent = file_get_contents($filePath);
        Assert::string($fileContent);

        $simpleXmlElement = simplexml_load_string($fileContent);
        Assert::isInstanceOf($simpleXmlElement, SimpleXMLElement::class);

        $this->data = $simpleXmlElement;
    }

    public function getData(): SimpleXMLElement
    {
        return $this->data;
    }

    public function getSourceFilePath(): string
    {
        return $this->filePath;
    }
}
