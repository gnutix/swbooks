<?php

declare(strict_types=1);

namespace Gnutix\Library\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Webmozart\Assert\Assert;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('gnutix_library');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        Assert::isInstanceOf($rootNode, ArrayNodeDefinition::class);

        $rootNode->append($this->getSourceFilePathNode());

        return $treeBuilder;
    }

    private function getSourceFilePathNode(): ScalarNodeDefinition
    {
        $treeBuilder = new TreeBuilder('source_file_path', 'scalar');
        /** @var ScalarNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        Assert::isInstanceOf($node, ScalarNodeDefinition::class);

        $node->isRequired()->cannotBeEmpty();

        return $node;
    }
}
