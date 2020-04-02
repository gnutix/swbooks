<?php

namespace Gnutix\Library\Dumper;

use Gnutix\Library\LibraryDumperInterface;
use Gnutix\Library\LibraryInterface;

/**
 * XML Library Dumper
 */
final class XmlLibraryDumper implements LibraryDumperInterface
{
    public function dump(LibraryInterface $library): void
    {
        throw new \RuntimeException('The dumper "'.self::class.'" is not yet implemented.');
    }
}
