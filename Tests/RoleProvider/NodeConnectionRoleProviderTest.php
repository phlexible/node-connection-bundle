<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Tests\RoleProvider;

use Phlexible\Bundle\NodeConnectionBundle\RoleProvider\NodeConnectionRoleProvider;
use PHPUnit\Framework\TestCase;

/**
 * Node connection role provider test.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class NodeConnectionRoleProviderTest extends TestCase
{
    public function testProvideRoles()
    {
        $provider = new NodeConnectionRoleProvider();

        $this->assertSame(array('ROLE_NODE_CONNECTIONS'), $provider->provideRoles());
    }

    public function testExposeRoles()
    {
        $provider = new NodeConnectionRoleProvider();

        $this->assertSame(array('ROLE_NODE_CONNECTIONS'), $provider->exposeRoles());
    }
}
