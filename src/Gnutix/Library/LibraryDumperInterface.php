<?php

namespace Gnutix\Library;

/**
 * Library Dumper Interface
 */
interface LibraryDumperInterface
{
    /**
     * @param \Gnutix\Library\LibraryInterface $library
     *
     * @return string
     */
    public function dump(LibraryInterface $library);
}
