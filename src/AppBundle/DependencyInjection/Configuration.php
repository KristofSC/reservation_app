<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        // itt állítjuk össze a configs arrayt, a fa alapján:
        $rootNode
            ->children()
                ->arrayNode('surgeries')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('surgeryName')
                    ->prototype('scalar')->end()
            ->end();

        return $treeBuilder;
    }
}