<?php

namespace Application;

use Gnutix\Kernel\TwigAwareKernel;
use Gnutix\StarWarsLibrary\DependencyInjection\Extension as StarWarsLibraryExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application Kernel
 */
final class AppKernel extends TwigAwareKernel
{
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return new Response(
            $this->container->get('twig')->render(
                'index.html.twig',
                [
                    'request' => $request,
                    'showLanguages' => $request->query->get('show-languages'),
                ]
            )
        );
    }

    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), [new StarWarsLibraryExtension()]);
    }
}
