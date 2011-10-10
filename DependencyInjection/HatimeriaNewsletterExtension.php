<?php

namespace Hatimeria\NewsletterBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\Config\Definition\Processor;

class HatimeriaNewsletterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $services = $config['mailing_services'];

        if (null !== $config['recipient_provider']) {
            $container->setAlias('hatimeria_newsletter.recipient_provider', $config['recipient_provider']);
        }
        $container->setAlias('hatimeria_newsletter.manager', $config['manager']);
        $container->setParameter('hatimeria_newsletter.mailing_services', $services);
    }
    
}