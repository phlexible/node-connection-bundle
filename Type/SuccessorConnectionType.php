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
 * Successor connection type
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class SuccessorConnectionType implements ConnectionTypeInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return 'successor';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_DIRECTED;
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
        return 'successor_title_source';
    }

    /**
     * @return string
     */
    public function getSourceText()
    {
        return 'successor_text_source';
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
        return 'successor_title_target';
    }

    /**
     * @return string
     */
    public function getTargetText()
    {
        return 'successor_text_target';
    }

    /**
     * @return string
     */
    public function getSourceIconClass()
    {
        return 'm-elementconnections-successor_source-icon';
    }

    /**
     * @return string
     */
    public function getTargetIconClass()
    {
        return 'm-elementconnections-successor_target-icon';
    }
}
