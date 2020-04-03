<?php

declare(strict_types=1);

namespace Gnutix\Library\Loader;

use Gnutix\Library\LoaderInterface;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

/**
 * YAML File Loader
 */
final class YamlFileLoader implements LoaderInterface
{
    private string $filePath;
    private array $data;

    public function __construct(string $filePath)
    {
        Assert::fileExists($filePath);
        $this->filePath = $filePath;

        $fileContent = file_get_contents($filePath);
        Assert::string($fileContent);

        $this->data = Yaml::parse($fileContent, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE + Yaml::PARSE_OBJECT);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getSourceFilePath(): string
    {
        return $this->filePath;
    }
}
