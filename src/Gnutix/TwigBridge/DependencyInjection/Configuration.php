<?php

namespace Gnutix\TwigBridge\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gnutix_twig');

        $rootNode->append($this->getTemplatePathsNode())
            ->append($this->getTwigOptionsNode());

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    protected function getTemplatePathsNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('templates_paths');

        $node
            ->requiresAtLeastOneElement()
            ->isRequired()
            ->cannotBeEmpty();
        $node->prototype('scalar')->end();

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    protected function getTwigOptionsNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('options');

        $node->defaultValue(array());
        $node->prototype('scalar')->end();

        return $node;
    }
}
