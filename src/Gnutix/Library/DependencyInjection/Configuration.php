<?php

namespace Gnutix\Library\DependencyInjection;

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
        $treeBuilder = new TreeBuilder('gnutix_library');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->append($this->getSourceFilePathNode());

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition
     */
    protected function getSourceFilePathNode()
    {
        $treeBuilder = new TreeBuilder('source_file_path', 'scalar');
        $node = $treeBuilder->getRootNode();

        $node->isRequired()->cannotBeEmpty();

        return $node;
    }
}
