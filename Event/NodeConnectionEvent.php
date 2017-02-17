<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Event;

use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Symfony\Component\EventDispatcher\Event;

/**
 * Node connection Event
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
class NodeConnectionEvent extends Event
{
    /**
     * @var NodeConnection
     */
    private $nodeConnection;

    /**
     * Constructor
     *
     * @param NodeConnection $nodeConnection
     */
    public function __construct(NodeConnection $nodeConnection)
    {
        $this->nodeConnection = $nodeConnection;
    }

    /**
     * @return NodeConnection
     */
    public function getNodeConnection()
    {
        return $this->nodeConnection;
    }
}
