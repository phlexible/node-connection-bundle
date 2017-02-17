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
 * Connection type interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface ConnectionTypeInterface
{
    const TYPE_DIRECTED = 'directed';
    const TYPE_UNDIRECTED = 'undirected';

    const ORIGIN_SOURCE = 'source';
    const ORIGIN_TARGET = 'target';

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getAllowedSourceElementTypeIds();

    /**
     * @return string
     */
    public function getSourceTitle();

    /**
     * @return string
     */
    public function getSourceText();

    /**
     * @return array
     */
    public function getAllowedTargetElementTypeIds();

    /**
     * @return string
     */
    public function getTargetTitle();

    /**
     * @return string
     */
    public function getTargetText();
}
