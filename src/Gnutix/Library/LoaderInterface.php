<?php

declare(strict_types=1);

namespace Gnutix\Library;

interface LoaderInterface
{
    public function __construct(string $fileName);

    /**
     * @return mixed
     */
    public function getData();

    public function getSourceFilePath(): string;
}
