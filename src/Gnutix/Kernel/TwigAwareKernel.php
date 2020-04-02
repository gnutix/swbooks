<?php

namespace Gnutix\Kernel;

use Gnutix\Twig\DependencyInjection\Extension as TwigExtension;

/**
 * Twig Aware Kernel
 */
abstract class TwigAwareKernel extends Kernel
{
    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), [new TwigExtension()]);
    }
}
