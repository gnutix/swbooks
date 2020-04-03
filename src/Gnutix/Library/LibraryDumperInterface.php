<?php

declare(strict_types=1);

namespace Gnutix\Library;

interface LibraryDumperInterface
{
    public function dump(LibraryInterface $library): string;
}
