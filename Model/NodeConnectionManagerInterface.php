<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Model;

use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;

/**
 * Node connection manager interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface NodeConnectionManagerInterface
{
    /**
     * @param integer $id
     *
     * @return NodeConnection|null
     */
    public function find($id);

    /**
     * @param integer $nodeId
     * @param string  $type
     *
     * @return NodeConnection[]
     */
    public function findByNodeId($nodeId, $type = null);

    /**
     * @param NodeConnection $nodeConnection
     */
    public function updateNodeConnection(NodeConnection $nodeConnection);

    /**
     * @param NodeConnection $nodeConnection
     */
    public function deleteNodeConnection(NodeConnection $nodeConnection);
}
