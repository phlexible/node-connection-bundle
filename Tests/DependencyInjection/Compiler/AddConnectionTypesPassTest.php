<?php

/*
 * This file is part of the phlexible element finder package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Tests\DependencyInjection\Compiler;

use Phlexible\Bundle\NodeConnectionBundle\DependencyInjection\Compiler\AddConnectionTypesPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register node connection types test.
 *
 * @author Stephan Wentz <swentz@brainbits.net>
 */
class AddConnectionTypesPassTest extends TestCase
{
    public function testPass()
    {
        $definition = new Definition();
        $definition->setArguments(array(array()));

        $container = $this->prophesize(ContainerBuilder::class);
        $container->findTaggedServiceIds('phlexible_node_connection.connection_type')->willReturn(array('foo' => array()));
        $container->findDefinition('phlexible_node_connection.connection_types')->willReturn($definition);

        $pass = new AddConnectionTypesPass();

        $pass->process($container->reveal());

        $this->assertEquals(array(array(new Reference('foo'))), $definition->getArguments());
    }
}
