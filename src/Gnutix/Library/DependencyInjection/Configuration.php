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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gnutix_library');

        $rootNode->append($this->getSourceFilePathNode());

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition
     */
    protected function getSourceFilePathNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('source_file_path', 'scalar');

        $node->isRequired()->cannotBeEmpty();

        return $node;
    }
}
