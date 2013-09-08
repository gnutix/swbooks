<?php

namespace Gnutix\Twig\DependencyInjection;

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

        $rootNode
            ->append($this->getAssetsDirNode())
            ->append($this->getTemplatePathsNode())
            ->append($this->getTwigOptionsNode());

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition
     */
    protected function getAssetsDirNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('assets_dir', 'scalar');

        $node->isRequired()
            ->cannotBeEmpty();

        return $node;
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

        $node
            ->append($this->getTwigOptionNode('debug'))
            ->append($this->getTwigOptionNode('charset'))
            ->append($this->getTwigOptionNode('base_template_class'))
            ->append($this->getTwigOptionNode('cache'))
            ->append($this->getTwigOptionNode('auto_reload'))
            ->append($this->getTwigOptionNode('strict_variables'))
            ->append($this->getTwigOptionNode('autoescape'))
            ->append($this->getTwigOptionNode('optimizations'))
        ;

        return $node;
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition
     */
    protected function getTwigOptionNode($name)
    {
        $treeBuilder = new TreeBuilder();

        return $treeBuilder->root($name, 'scalar');
    }
}
