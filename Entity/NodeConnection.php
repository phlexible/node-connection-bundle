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
 * Node connection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 *
 * @ORM\Entity
 * @ORM\Table(name="property")
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
     * @ORM\Column(type="integer")
     */
    private $source;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $target;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $sortSource;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $sortTarget;

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
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param int $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return int
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param int $target
     *
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return int
     */
    public function getSortSource()
    {
        return $this->sortSource;
    }

    /**
     * @param int $sortSource
     *
     * @return $this
     */
    public function setSortSource($sortSource)
    {
        $this->sortSource = $sortSource;

        return $this;
    }

    /**
     * @return int
     */
    public function getSortTarget()
    {
        return $this->sortTarget;
    }

    /**
     * @param int $sortTarget
     *
     * @return $this
     */
    public function setSortTarget($sortTarget)
    {
        $this->sortTarget = $sortTarget;

        return $this;
    }
}
