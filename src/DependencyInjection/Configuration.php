<?php

namespace Apb\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\BooleanNodeDefinition;
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
                    ->append($this->getAllowedControllers())
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function getAllowedControllers(): ArrayNodeDefinition
    {
        $node = new ArrayNodeDefinition('allowed_controllers');

        $node
            ->beforeNormalization()
            ->always(function ($v) {
                if ($v === '*') {
                    return ['*'];
                }

                return $v;
            })
            ->end()
            ->prototype('scalar')->end()
        ;

        return $node;
    }
}