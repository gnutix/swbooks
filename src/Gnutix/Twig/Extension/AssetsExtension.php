<?php

namespace Gnutix\Twig\Extension;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Assets Extension
 */
final class AssetsExtension extends AbstractExtension
{
    /** @var string */
    protected $publicDir;

    /**
     * @param string $publicDir
     */
    public function __construct($publicDir)
    {
        $this->publicDir = $publicDir;
    }

    public function getName()
    {
        return 'gnutix_twig_assets_extension';
    }

    public function getFunctions()
    {
        return [new TwigFunction('asset', [$this, 'getAssetPath'])];
    }

    /**
     * @param string                                    $asset
     * @param bool                                      $throwException
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAssetPath(Request $request, $asset, $throwException = true)
    {
        $finder = new Finder();
        $assetPathInfo = pathinfo($asset);

        $assetFound = $finder->in(rtrim($this->publicDir, '/').'/'.ltrim($assetPathInfo['dirname'], '/'))
            ->files()
            ->name($assetPathInfo['filename'].'.'.$assetPathInfo['extension'])
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
