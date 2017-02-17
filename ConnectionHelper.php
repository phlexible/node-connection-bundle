<?php

/**
 * MAKEweb
 *
 * PHP Version 5
 *
 * @category    MAKEweb
 * @package     Makeweb_ElementConnections
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 * @version     SVN: $Id: Generator.php 2312 2007-01-25 18:46:27Z swentz $
 */

/**
 * Element connection helper
 *
 * @category    MAKEweb
 * @package     Makeweb_ElementConnections
 * @author      Phillip Look <pl@brainbits.net>
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 *
 * @property string  $id
 * @property object  $type
 * @property integer $source
 * @property integer $target
 * @property string  $origin
 */
class Makeweb_ElementConnections_ConnectionHelper
{
    /**
     * @var Makeweb_ElementConnections_Manager
     */
    protected $_elementConnectionsManager;

    /**
     * @var Makeweb_Elements_Tree_TreeHelper
     */
    protected $_treeHelper;

    /**
     * Constructor
     *
     * @param Makeweb_ElementConnections_Manager $elementConnectionManager
     * @param Makeweb_Elements_Tree_TreeHelper   $treeHelper
     */
    public function __construct(Makeweb_ElementConnections_Manager $elementConnectionManager,
                                Makeweb_Elements_Tree_TreeHelper   $treeHelper)
    {
        $this->_elementConnectionsManager = $elementConnectionManager;
        $this->_treeHelper                = $treeHelper;
    }

    /**
     * Get titles of connected elements as an array.
     *
     * @param integer $sourceTid
     * @param string  $language
     * @param string  $type
     * @param string  $title
     *
     * @return array
     */
    public function getConnectionTitles($sourceTid, $language, $type = null, $title = 'navigation')
    {
        $connections = $this->_elementConnectionsManager
            ->getForOnlineTid($sourceTid, $language, $type);

        $titles = array();
        foreach ($connections as $connection)
        {
            /* @var $connection Makeweb_ElementConnections_Connection */
            $targetTid = ($connection->source == $sourceTid)
                ? (integer) $connection->target
                : (integer) $connection->source;

            $title = $this->_treeHelper->getOnlineTitleByTid($targetTid, $language);
            if (strlen($title))
            {
                $titles[$targetTid] = $title;
            }
        }

        asort($titles);

        return $titles;
    }

    /**
     * Get titles of connected elements as a coma seperated string.
     *
     * @param integer $sourceTid
     * @param string  $language
     * @param string  $type
     * @param string  $title
     *
     * @return string
     */
    public function getConnectionTitlesAsString($sourceTid,
                                                $language,
                                                $type = null,
                                                $title = 'navigation')
    {
        $titles = $this->getConnectionTitles($sourceTid, $language, $type, $title);

        return implode(', ', $titles);
    }
}
