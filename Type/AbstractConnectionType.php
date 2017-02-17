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
 * Abstract connection type
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
abstract class AbstractConnectionType implements ConnectionTypeInterface
{
    const TYPE_DIRECTED = 'directed';
    const TYPE_UNDIRECTED = 'undirected';

    const ORIGIN_SOURCE = 'source';
    const ORIGIN_TARGET = 'target';

    protected $_key = 'generic';
    protected $_type = self::TYPE_UNDIRECTED;

    /**
     * @var MWF_Core_Translations_Translation
     */
    protected $_t9n = null;

    /**
     * @var Makeweb_Elementtypes_Elementtype_Manager
     */
    protected $_elementTypeManager;

    /**
     * Constructor
     *
     * @param MWF_Core_Translations_Translation
     */
    public function __construct(MWF_Core_Translations_Translation $t9n,
                                Makeweb_Elementtypes_Elementtype_Manager $elementTypeManager)
    {
        $this->_t9n = $t9n;
        $this->_elementTypeManager = $elementTypeManager;
    }

    public function getKey()
    {
        return $this->_key;
    }

    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get allowed element types (unique ids).
     *
     * @param string $origin
     *
     * @return array
     */
    public function getAllowedElementTypeUniqueIds($origin)
    {
        return array();
    }

    /**
     * Get allowed element types (ids).
     *
     * @param string $origin
     *
     * @return array
     */
    public function getAllowedElementTypeIds($origin)
    {
        $allowedElementTypeIds = array();

        $allowedElementTypeUniqueIds = $this->getAllowedElementTypeUniqueIds($origin);
		foreach ($allowedElementTypeUniqueIds as $uniqueId)
        {
            try
            {
                $allowedElementTypeIds[] =
                    $this->_elementTypeManager->getElementTypeIDByUniqueID($uniqueId);
            }
            catch (Makeweb_Elementtypes_Elementtype_Manager_Exception $e)
            {
                // result array must not be empty,
                // that would allow creation of connection type for all element types
                $allowedElementTypeIds[$uniqueId] = '#unknown unique id#';
                MWF_Log::exception($e);
            }
        }

        return $allowedElementTypeIds;
    }

    public function getText($origin, $text, $language)
    {
        $tpl = $this->getTextTemplate($origin, $language);

        return str_replace('{0}', $text, $tpl);
    }
}
