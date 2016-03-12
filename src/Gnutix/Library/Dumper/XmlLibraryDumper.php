<?php

namespace Gnutix\Library\Dumper;

use Gnutix\Library\LibraryDumperInterface;
use Gnutix\Library\LibraryInterface;

/**
 * XML Library Dumper
 */
class XmlLibraryDumper implements LibraryDumperInterface
{
    /**
     * {@inheritDoc}
     */
    public function dump(LibraryInterface $library)
    {
        throw new \RuntimeException('The dumper "'.__CLASS__.'" is not yet implemented.');
    }
}
