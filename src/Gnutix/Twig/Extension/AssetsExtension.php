<?php

namespace Gnutix\Twig\Extension;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Assets Extension
 */
class AssetsExtension extends \Twig_Extension
{
    /** @var string */
    protected $webDir;

    /**
     * @param string $webDir
     */
    public function __construct($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gnutix_twig_assets_extension';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'getAssetPath')),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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

        $assetFound = $finder->in(rtrim($this->webDir, '/').'/'.ltrim($assetPathInfo['dirname'], '/'))
            ->files()
            ->name($assetPathInfo['filename'].'.'.$assetPathInfo['extension'])
            ->count();

        if (0 < $assetFound) {
            return rtrim($request->getBasePath(), '/').'/'.ltrim($asset, '/');
        }

        if ($throwException) {
            throw new \InvalidArgumentException('The asset "'.$asset.'" could not be found in "'.$this->webDir.'".');
        }

        return '';
    }
}
