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
    protected $_key = 'successor';
    protected $_type = self::TYPE_DIRECTED;

    protected $_iconClassSource = 'm-elementconnections-successor_source-icon';
    protected $_iconClassTarget = 'm-elementconnections-successor_target-icon';

    public function getTitle($origin, $language)
    {
        if ($origin === self::ORIGIN_SOURCE)
        {
            return $this->_t9n->elementconnections->successor_title_source;
        }
        elseif ($origin === self::ORIGIN_TARGET)
        {
            return $this->_t9n->elementconnections->successor_title_target;
        }

        throw new Makeweb_ElementConnections_Exception('Unknown origin "' . $origin . '"');
    }

    public function getIconClass($origin)
    {
        if ($origin === self::ORIGIN_SOURCE)
        {
            return $this->_iconClassSource;
        }
        elseif ($origin === self::ORIGIN_TARGET)
        {
            return $this->_iconClassTarget;
        }

        throw new Makeweb_ElementConnections_Exception('Unknown origin "' . $origin . '"');
    }

    public function getTextTemplate($origin, $language)
    {
        if ($origin === self::ORIGIN_SOURCE)
        {
            return $this->_t9n->elementconnections->successor_text_source;
        }
        elseif ($origin === self::ORIGIN_TARGET)
        {
            return $this->_t9n->elementconnections->successor_text_target;
        }

        throw new Makeweb_ElementConnections_Exception('Unknown origin "' . $origin . '"');
    }
}
