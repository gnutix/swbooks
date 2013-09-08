<?php

namespace Gnutix\Twig\Extension;

use Symfony\Component\Finder\Finder;

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
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'getAssetPath')),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gnutix_twig_extension';
    }

    /**
     * @param string $asset
     * @param bool   $throwException
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAssetPath($asset, $throwException = true)
    {
        $finder = new Finder();
        $assetPathInfo = pathinfo($asset);

        $assetFound = $finder->in(rtrim($this->webDir, '/').'/'.ltrim($assetPathInfo['dirname'], '/'))
            ->files()
            ->name($assetPathInfo['filename'].'.'.$assetPathInfo['extension'])
            ->count();

        if (0 < $assetFound) {
            return $asset;
        }

        if ($throwException) {
            throw new \InvalidArgumentException('The asset "'.$asset.'" could not be found in "'.$this->webDir.'".');
        }

        return '';
    }
}
