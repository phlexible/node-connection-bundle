<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Tests;

use Phlexible\Bundle\NodeConnectionBundle\PhlexibleNodeConnectionBundle;
use PHPUnit\Framework\TestCase;

/**
 * Node connection bundle test.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class PhlexibleNodeConnectionBundleTest extends TestCase
{
    public function testBundle()
    {
        $bundle = new PhlexibleNodeConnectionBundle();

        $this->assertSame('PhlexibleNodeConnectionBundle', $bundle->getName());
    }
}
