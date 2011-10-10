<?php

namespace Hatimeria\NewsletterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hatimeria_newsletter');

        $rootNode
            ->children()
                //->booleanNode('use_controller')->defaultTrue()->end()
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