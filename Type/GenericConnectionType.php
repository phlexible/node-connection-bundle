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
class GenericConnectionType implements ConnectionTypeInterface
{
    protected $_key = 'generic';
    protected $_type = self::TYPE_UNDIRECTED;

    protected $_iconClass = 'm-elementconnections-component-icon';

    public function getTitle($origin, $language)
    {
        return $this->_t9n->elementconnections->generic_title;
    }

    public function getIconClass($origin)
    {
        return $this->_iconClass;
    }

    public function getTextTemplate($origin, $language)
    {
        return $this->_t9n->elementconnections->generic_text;
    }
}
