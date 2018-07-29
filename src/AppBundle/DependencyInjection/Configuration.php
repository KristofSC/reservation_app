<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $this->addSurgerySection($rootNode);
        $this->addBreadcrumbSection($rootNode);
        $this->addDateLimit($rootNode);
        $this->addHoursSection($rootNode);

        return $treeBuilder;

    }

    private function addSurgerySection(ArrayNodeDefinition $rootNode)
    {
        // itt állítjuk össze a configs arrayt, a fa alapján:
        $rootNode
            ->children()
                    ->arrayNode('surgeries')
                    ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('surgeryName')
                            ->prototype('scalar')
                    ->end()
            ->end();
    }

    private function addBreadcrumbSection(ArrayNodeDefinition $rootNode)
    {
        // itt állítjuk össze a configs arrayt, a fa alapján:
        $rootNode
            ->children()
                    ->arrayNode('breadcrumb')
                    ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('breadcrumbElement')
                            ->info('Itt adhatók meg a breadcrumb elemei - a sorrend a hierarchiát jelenti!')
                            ->prototype('scalar')
                    ->end()
            ->end();

    }

    private function addHoursSection(ArrayNodeDefinition $rootNode)
    {
        // itt állítjuk össze a configs arrayt, a fa alapján:
        $rootNode
            ->children()
                    ->arrayNode('hours')
                    ->requiresAtLeastOneElement()
                            ->info('A napokhoz tartozó órák száma!')
                            ->prototype('integer')
                    ->end()
            ->end();

    }

    private function addDateLimit(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                    ->integerNode('dateLimit')
                            ->defaultValue(60)
                    ->end()
            ->end();
    }
}