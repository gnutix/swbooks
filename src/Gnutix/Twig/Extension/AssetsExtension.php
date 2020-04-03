<?php

declare(strict_types=1);

namespace Gnutix\Twig\Extension;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AssetsExtension extends AbstractExtension
{
    protected string $publicDir;

    public function __construct(string $publicDir)
    {
        $this->publicDir = $publicDir;
    }

    public function getName(): string
    {
        return 'gnutix_twig_assets_extension';
    }

    public function getFunctions(): array
    {
        return [new TwigFunction('asset', [$this, 'getAssetPath'])];
    }

    public function getAssetPath(Request $request, string $asset, bool $throwException = true): string
    {
        $finder = new Finder();

        $assetFound = $finder->in(rtrim($this->publicDir, '/').'/'.ltrim(pathinfo($asset, PATHINFO_DIRNAME), '/'))
            ->files()
            ->name(pathinfo($asset, PATHINFO_FILENAME).'.'.pathinfo($asset, PATHINFO_EXTENSION))
            ->count();

        if (0 < $assetFound) {
            return rtrim($request->getBasePath(), '/').'/'.ltrim($asset, '/');
        }

        if ($throwException) {
            throw new \InvalidArgumentException('The asset "'.$asset.'" could not be found in "'.$this->publicDir.'".');
        }

        return '';
    }
}
