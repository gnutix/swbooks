<?php

declare(strict_types=1);

namespace Gnutix\Library;

interface LibraryFactoryInterface
{
    public function getLibrary(): LibraryInterface;

    public function getLibraryDumper(): LibraryDumperInterface;
}
