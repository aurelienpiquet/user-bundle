<?php

namespace Apb\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('user_bundle');

        $treeBuilder
            ->getRootNode()
            ->children()
            ->arrayNode('configuration')
            ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('mailer')->defaultValue(false)->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}