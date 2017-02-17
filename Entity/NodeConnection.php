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

use Doctrine\ORM\Mapping as ORM;

/**
 * Node connection.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 *
 * @ORM\Entity
 * @ORM\Table(name="node_connection")
 */
class NodeConnection
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(name="source_node_id", type="integer")
     */
    private $sourceNodeId;

    /**
     * @var int
     * @ORM\Column(name="target_node_id", type="integer")
     */
    private $targetNodeId;

    /**
     * @var int
     * @ORM\Column(name="source_sort", type="integer")
     */
    private $sourceSort;

    /**
     * @var int
     * @ORM\Column(name="target_sort", type="integer")
     */
    private $targetSort;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getSourceNodeId()
    {
        return $this->sourceNodeId;
    }

    /**
     * @param int $sourceNodeId
     *
     * @return $this
     */
    public function setSourceNodeId($sourceNodeId)
    {
        $this->sourceNodeId = $sourceNodeId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTargetNodeId()
    {
        return $this->targetNodeId;
    }

    /**
     * @param int $targetNodeId
     *
     * @return $this
     */
    public function setTargetNodeId($targetNodeId)
    {
        $this->targetNodeId = $targetNodeId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSourceSort()
    {
        return $this->sourceSort;
    }

    /**
     * @param int $sourceSort
     *
     * @return $this
     */
    public function setSourceSort($sourceSort)
    {
        $this->sourceSort = $sourceSort;

        return $this;
    }

    /**
     * @return int
     */
    public function getTargetSort()
    {
        return $this->targetSort;
    }

    /**
     * @param int $targetSort
     *
     * @return $this
     */
    public function setTargetSort($targetSort)
    {
        $this->targetSort = $targetSort;

        return $this;
    }
}
