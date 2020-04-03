<?php

declare(strict_types=1);

namespace Gnutix\Kernel;

use Gnutix\Twig\DependencyInjection\Extension as TwigExtension;
use Symfony\Bundle\TwigBundle\DependencyInjection\Compiler\TwigEnvironmentPass as SymfonyTwigEnvironmentPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class TwigAwareKernel extends Kernel
{
    protected function getExtensions(): array
    {
        return array_merge(parent::getExtensions(), [new TwigExtension()]);
    }

    protected function addCompilerPasses(ContainerBuilder $container): void
    {
        parent::addCompilerPasses($container);

        $container->addCompilerPass(new SymfonyTwigEnvironmentPass());
    }
}
