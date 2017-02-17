<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Connection controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class ListController extends Controller
{
    public function indexAction()
    {
        $tid = $this->_getParam('tid', 0);
        $language = $this->_getParam('language', 'de'); // TODO

        $container = $this->getContainer();
        $connectionsManager = $container->elementConnectionsManager;
        $elementVersionManager = $container->elementsVersionManager;
        $treeManager = $container->elementsTreeManager;

        $connections = $connectionsManager->getForTid($tid);

        $sourceNode           = $treeManager->getNodeByNodeId($tid);
        $sourceElementVersion = $elementVersionManager->getLatest($sourceNode->getEid());
        $sourceElementTypeId  = $sourceElementVersion->getElementTypeID();

        $result = array();
        foreach ($connections as $connection)
        {
            if ($tid == $connection->source)
            {
                $source = $connection->source;
                $target = $connection->target;
                $sort = $connection->sortSource;

            }
            else
            {
                $source = $connection->target;
                $target = $connection->source;
                $sort = $connection->sortTarget;
            }

            try
            {
                $node = $treeManager->getNodeByNodeId($target);
            }
            catch (Makeweb_Elements_Tree_Exception $e)
            {
                // no db constraint -> nodes may me missing
                MWF_Log::exception($e);
                continue;
            }

            $elementVersion = $elementVersionManager->getLatest($node->getEid());

            $result[] = array(
                'id'         => $connection->id,
                'new'        => 0,
                'type'       => $connection->type->getKey(),
                'iconCls'    => $connection->type->getIconClass($connection->origin),
                'origin'     => $connection->origin,
                'source'     => $source,
                'target'     => $target,
                'typeText'   => $connection->type->getTitle($connection->origin, $language),
                'targetText' => $elementVersion->getBackendTitle($language) . ' [' . $target . ']',
                'sort'       => $sort
            );
        }

        $allTypes = $connectionsManager->getAllTypes();

        $types = array();
        foreach ($allTypes as $typeKey => $type)
        {
            /* @var $type Makeweb_ElementConnections_Type_Abstract */
            $allowedElementTypeIdsSource = $type->getAllowedElementTypeIds(
                Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE
            );

            if (!count($allowedElementTypeIdsSource)
                || in_array($sourceElementTypeId, $allowedElementTypeIdsSource))
            {
                $types[$typeKey . '_' . Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE] = array(
                    'key'     => $typeKey,
                    'type'    => $type->getType(),
                    'origin'  => Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE,
                    'title'   => $type->getTitle(Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE, $language),
                    'textTpl' => $type->getTextTemplate(Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE, $language),
                    'iconCls' => $type->getIconClass(Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE),
                    'allowedElementTypeIds' => $type->getAllowedElementTypeIds(Makeweb_ElementConnections_Type_Abstract::ORIGIN_TARGET),
                );
            }

            $allowedElementTypeIdsTarget = $type->getAllowedElementTypeIds(
                Makeweb_ElementConnections_Type_Abstract::ORIGIN_TARGET
            );

            if (!count($allowedElementTypeIdsTarget)
                || in_array($sourceElementTypeId, $allowedElementTypeIdsTarget))
            {
                $types[$typeKey . '_' . Makeweb_ElementConnections_Type_Abstract::ORIGIN_TARGET] = array(
                    'key'     => $typeKey,
                    'type'    => $type->getType(),
                    'origin'  => Makeweb_ElementConnections_Type_Abstract::ORIGIN_TARGET,
                    'title'   => $type->getTitle(Makeweb_ElementConnections_Type_Abstract::ORIGIN_TARGET, $language),
                    'textTpl' => $type->getTextTemplate(Makeweb_ElementConnections_Type_Abstract::ORIGIN_TARGET, $language),
                    'iconCls' => $type->getIconClass(Makeweb_ElementConnections_Type_Abstract::ORIGIN_TARGET, $language),
                    'allowedElementTypeIds' => $type->getAllowedElementTypeIds(Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE),
                );
            }
        }

        $this->_response->setAjaxPayload(
            array(
                'connections' => $result,
                'types'       => $types,
            )
        );
    }

    public function saveAction()
    {
        $tid = $this->_getParam('tid');
        $data = $this->_getParam('data');
        $data = Zend_Json::decode($data);

        $container = $this->getContainer();
        $connectionsManager = $container->elementConnectionsManager;

        try
        {
            $connections = $connectionsManager->getForTid($tid);
            $allTypes = $connectionsManager->getAllTypes();

            foreach ($data as $row)
            {
                $connection = $connectionsManager->getById($row['id']);

                if (!$connection)
                {
                    $connection = new Makeweb_ElementConnections_Connection();
                }
                else
                {
                    unset($connections[$connection->id]);
                }

                $connection->type   = $allTypes[$row['type']];
                $connection->origin = $row['origin'];
                if ($connection->origin === Makeweb_ElementConnections_Type_Abstract::ORIGIN_SOURCE)
                {
                    $connection->source = (integer)$row['source'];
                    $connection->target = (integer)$row['target'];
                    $connection->sortSource = (integer)$row['sort'];
                }
                else
                {
                    $connection->source = (integer)$row['target'];
                    $connection->target = (integer)$row['source'];
                    $connection->sortTarget = (integer)$row['sort'];
                }

                $connectionsManager->save($connection);
            }

            foreach ($connections as $connection)
            {
                $connectionsManager->delete($connection);
            }

            $result = MWF_Ext_Result::encode(true, 0, 'Connections saved.');
        }
        catch (Exception $e)
        {
            $result = MWF_Ext_Result::encode(false, 0, $e->getMessage());
        }

        $this->_response->setAjaxPayload($result);
    }
}
