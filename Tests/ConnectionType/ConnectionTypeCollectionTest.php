<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Tests\ConnectionType;

use Phlexible\Bundle\NodeConnectionBundle\ConnectionType\ConnectionTypeCollection;
use Phlexible\Bundle\NodeConnectionBundle\ConnectionType\ConnectionTypeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Connection type collection test.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class ConnectionTypeCollectionTest extends TestCase
{
    public function testTypesInConstructor()
    {
        $type = $this->prophesize(ConnectionTypeInterface::class);

        $types = new ConnectionTypeCollection(array($type->reveal()));

        $this->assertAttributeCount(1, 'types', $types);
    }

    public function testTypesCanBeEmpty()
    {
        $types = new ConnectionTypeCollection(array());

        $this->assertAttributeCount(0, 'types', $types);
    }

    public function testHas()
    {
        $type = $this->prophesize(ConnectionTypeInterface::class);
        $type->getKey()->willReturn('foo');

        $types = new ConnectionTypeCollection(array($type->reveal()));

        $this->assertTrue($types->has('foo'));
        $this->assertFalse($types->has('bar'));
    }

    public function testGet()
    {
        $type = $this->prophesize(ConnectionTypeInterface::class);
        $type->getKey()->willReturn('foo');

        $types = new ConnectionTypeCollection(array($type->reveal()));

        $this->assertSame($types->get('foo'), $type->reveal());
        $this->assertNull($types->get('bar'));
    }

    public function testCountable()
    {
        $type = $this->prophesize(ConnectionTypeInterface::class);

        $types = new ConnectionTypeCollection(array($type->reveal()));

        $this->assertCount(1, $types);
    }

    public function testIteratable()
    {
        $type = $this->prophesize(ConnectionTypeInterface::class);

        $types = new ConnectionTypeCollection(array($type->reveal()));

        $expectedTypes = array($type->reveal());

        foreach ($types as $type) {
            $expectedType = array_shift($expectedTypes);
            $this->assertSame($expectedType, $type);
        }
    }
}
