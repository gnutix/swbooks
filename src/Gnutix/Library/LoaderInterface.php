<?php

namespace Gnutix\Library;

/**
 * Loader Interface
 */
interface LoaderInterface
{
    /**
     * @param string $fileName
     */
    public function __construct($fileName);

    public function getData();

    /**
     * @return string
     */
    public function getSourceFilePath();
}
