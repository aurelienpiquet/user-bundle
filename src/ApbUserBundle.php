<?php

namespace Apb\UserBundle;

use Apb\UserBundle\DependencyInjection\UserBundleExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class ApbUserBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new UserBundleExtension();
    }

//    public function configure(DefinitionConfigurator $definition): void
//    {
//        $definition
//            ->rootNode()
//                ->children()
//                    ->arrayNode('mailer')
//                        ->children()
//                            ->scalarNode('apiUrl')->end()
////                            ->scalarNode('projectName')->end()
////                            ->scalarNode('apiUrl')->end()
////                            ->scalarNode('sender')->end()
////                            ->scalarNode('senderStr')->end()
//                        ->end()
//                    ->end()
//            ->end()
//        ;
//    }

//    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
//    {
//        // the "$config" variable is already merged and processed so you can
//        // use it directly to configure the service container (when defining an
//        // extension class, you also have to do this merging and processing)
//        $container
//            ->services()
//            ->get('user_bundle.mailer')
//            ->arg(0, $config['mailer']['apiUrl'])
//        ;
//    }
}