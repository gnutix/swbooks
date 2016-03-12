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

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return string
     */
    public function getSourceFilePath();
}
