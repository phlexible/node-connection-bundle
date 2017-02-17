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

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Generic connection type.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class ConnectionTypeCollection implements Countable, IteratorAggregate
{
    /**
     * @var ConnectionTypeInterface[]
     */
    private $types = array();

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
        $this->types[$type->getKey()] = $type;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->types[$key]);
    }

    /**
     * @param string $key
     *
     * @return ConnectionTypeInterface
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->types[$key];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->types);
    }

    /**
     * @return ConnectionTypeInterface[]|ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->types, ArrayIterator::ARRAY_AS_PROPS);
    }
}
