<?php

namespace Hatimeria\NewsletterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates configuration for bundle
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hatimeria_newsletter');

        $rootNode
            ->children()
                ->scalarNode('sender')->isRequired()->end()
                ->scalarNode('recipient_provider')->defaultNull()->end()
                ->scalarNode('manager')->defaultValue('hatimeria_newsletter.manager.default')->end()
                ->arrayNode('mailing_services')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}