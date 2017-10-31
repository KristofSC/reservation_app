<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('parameters.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // paraméter beállítása a configurationből:
        if (!empty($config['surgeries'])) {
            $container->setParameter('app.surgeries', $config['surgeries']);
        }
    }

}