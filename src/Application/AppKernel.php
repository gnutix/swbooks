<?php

declare(strict_types=1);

namespace Application;

use Gnutix\Kernel\TwigAwareKernel;
use Gnutix\StarWarsLibrary\DependencyInjection\Extension as StarWarsLibraryExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class AppKernel extends TwigAwareKernel
{
    public function handle(Request $request, int $type = self::MASTER_REQUEST, bool $catch = true): Response
    {
        /** @var Environment $twig */
        $twig = $this->getContainer()->get('twig');
        Assert::isInstanceOf($twig, Environment::class);

        return new Response(
            $twig->render(
                'index.html.twig',
                [
                    'request' => $request,
                    'showLanguages' => $request->query->get('show-languages'),
                ]
            )
        );
    }

    protected function getExtensions(): array
    {
        return array_merge(parent::getExtensions(), [new StarWarsLibraryExtension()]);
    }
}
