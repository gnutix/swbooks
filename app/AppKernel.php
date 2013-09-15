<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Gnutix\Kernel\TwigAwareKernel;

/**
 * Application Kernel
 */
class AppKernel extends TwigAwareKernel
{
    /**
     * {@inheritDoc}
     */
    protected function getExtensions()
    {
        return array_merge(
            parent::getExtensions(),
            array(
                new Gnutix\StarWarsLibrary\DependencyInjection\Extension
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return new Response(
            $this->container->get('twig')->render(
                'index.html.twig',
                array(
                    'request' => $request,
                    'showLanguages' => $request->query->get('show-languages'),
                )
            )
        );
    }
}
