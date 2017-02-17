<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\ConnectionType;

/**
 * Generic connection type
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class ConnectionTypeCollection
{
    /**
     * @var ConnectionTypeInterface[]
     */
    private $types;

    /**
     * @param ConnectionTypeInterface[] $types
     */
    public function __construct(array $types)
    {
        foreach ($types as $type) {
            $this->add($type);
        }
    }

    /**
     * @param ConnectionTypeInterface $type
     */
    private function add(ConnectionTypeInterface $type)
    {
        $this->types[] = $type;
    }
}
