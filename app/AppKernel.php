<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Gnutix\Application\Kernel;

/**
 * Application Kernel
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritDoc}
     */
    protected function getExtensions()
    {
        return array(
            new Gnutix\Twig\DependencyInjection\Extension,
            new Gnutix\StarWarsLibrary\DependencyInjection\Extension
        );
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        // Generate the web page
        return new Response(
            $this->container->get('twig')->render(
                'index.html.twig',
                array(
                    'request' => $request,
                    'library' => $this->container->get('gnutix_library.library_factory')->getLibrary(),
                )
            )
        );
    }
}
