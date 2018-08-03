<?php
declare(strict_types = 1);

namespace App\AppBundle\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('parameters.yml');
        $loader->load('services.yml');

//        $xmlLoader = new XmlFileLoader(
//            $container,
//            new FileLocator(__DIR__ . '/../Resources/config')
//        );
//
//        $xmlLoader->load('providers.xml');
//        $xmlLoader->load('services.xml');
//
//        $environment = $container->getParameter('kernel.environment');
//
//        try {
//            $loader->load($environment . DIRECTORY_SEPARATOR . 'services.yml');
//            $loader->load($environment . DIRECTORY_SEPARATOR . 'parameters.yml');
//        } catch (InvalidArgumentException $e) {
//            // todo: log exception if config file does not exist
//        }
    }
}
