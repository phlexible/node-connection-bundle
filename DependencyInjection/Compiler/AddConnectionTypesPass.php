<?php

/*
 * This file is part of the phlexible element finder package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register node connection types.
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
class AddConnectionTypesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $types = [];
        foreach ($container->findTaggedServiceIds('phlexible_node_connection.connection_type') as $id => $attributes) {
            $types[] = new Reference($id);
        }
        $container->findDefinition('phlexible_node_connection.connection_types')->replaceArgument(0, $types);
    }
}
