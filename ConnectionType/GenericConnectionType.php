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
 * Generic connection type.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class GenericConnectionType implements ConnectionTypeInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return 'generic';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_UNDIRECTED;
    }

    /**
     * @return array
     */
    public function getAllowedSourceElementTypeIds()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getSourceTitle()
    {
        return 'generic_source_title';
    }

    /**
     * @return string
     */
    public function getSourceText()
    {
        return 'generic_source_text';
    }

    /**
     * @return array
     */
    public function getAllowedTargetElementTypeIds()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getTargetTitle()
    {
        return 'generic_target_title';
    }

    /**
     * @return string
     */
    public function getTargetText()
    {
        return 'generic_target_text';
    }

    /**
     * @return string
     */
    public function getSourceIconClass()
    {
        return 'p-nodeconnection-component-icon';
    }

    /**
     * @return string
     */
    public function getTargetIconClass()
    {
        return 'p-nodeconnection-component-icon';
    }
}
