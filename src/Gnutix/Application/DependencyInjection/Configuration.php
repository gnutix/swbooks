<?php

namespace Gnutix\Application\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 */
class Configuration implements ConfigurationInterface
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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gnutix_application');

        $rootNode->append($this->getWebDirNode());

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition
     */
    protected function getWebDirNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('web_dir', 'scalar');

        $node->defaultValue($this->webDir)->cannotBeEmpty();

        return $node;
    }
}
