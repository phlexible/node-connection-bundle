<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle;

use Phlexible\Bundle\NodeConnectionsBundle\DependencyInjection\Compiler\AddConnectionTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Node connections bundle
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class PhlexibleNodeConnectionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddConnectionTypesPass());
    }
}
