<?php

declare(strict_types=1);

namespace Gnutix\Library\Dumper;

use BadMethodCallException;
use Gnutix\Library\LibraryDumperInterface;
use Gnutix\Library\LibraryInterface;

final class XmlLibraryDumper implements LibraryDumperInterface
{
    public function dump(LibraryInterface $library): string
    {
        throw new BadMethodCallException('The dumper "'.self::class.'" is not yet implemented.');
    }
}
