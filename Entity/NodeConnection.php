<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Entity;

use Phlexible\Bundle\NodeConnectionBundle\ConnectionType\ConnectionTypeInterface;

/**
 * Node connection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class NodeConnection
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var ConnectionTypeInterface
     */
    private $type;

    /**
     * @var int
     */
    private $source;

    /**
     * @var int
     */
    private $target;

    /**
     * @var string
     */
    private $origin;

    /**
     * @var string
     */
    private $sortSource;

    /**
     * @var string
     */
    private $sortTarget;
}
