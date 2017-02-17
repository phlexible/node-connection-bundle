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
 * Node connection manager interface.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface NodeConnectionManagerInterface
{
    /**
     * @param int $id
     *
     * @return NodeConnection|null
     */
    public function find($id);

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return NodeConnection|null
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return NodeConnection[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param int $nodeId
     *
     * @return NodeConnection[]
     */
    public function findByNodeId($nodeId);

    /**
     * @param NodeConnection $nodeConnection
     */
    public function updateNodeConnection(NodeConnection $nodeConnection);

    /**
     * @param NodeConnection $nodeConnection
     */
    public function deleteNodeConnection(NodeConnection $nodeConnection);
}
