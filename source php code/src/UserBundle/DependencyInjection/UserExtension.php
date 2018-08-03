<?php
declare(strict_types = 1);

namespace App\UserBundle\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Config\FileLocator;

class UserExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $environment = $container->getParameter('kernel.environment');

        $xmlLoader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $files = [
            'services.xml',
        ];

        array_map(function ($file) use ($xmlLoader, $environment) {

            $xmlLoader->load($file);

            try {
                $configFile = $environment . DIRECTORY_SEPARATOR . $file;
                $xmlLoader->load($configFile);
            } catch (InvalidArgumentException $e) {
                // todo log something
            }
        }, $files);
    }
}
