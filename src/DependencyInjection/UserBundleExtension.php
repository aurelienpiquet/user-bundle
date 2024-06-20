<?php

namespace Apb\UserBundle\DependencyInjection;

use Apb\MailerBundle\DependencyInjection\Configuration;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class UserBundleExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config'),
        );

        $loader->load('services.yaml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            if ($value) {
                $container->setParameter('user_bundle.' . $key, $value);
            }
        }
    }
}