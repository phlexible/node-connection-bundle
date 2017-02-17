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

use Phlexible\Bundle\NodeConnectionBundle\ConnectionType\ConnectionTypeCollection;
use Phlexible\Bundle\NodeConnectionBundle\Doctrine\NodeConnectionService;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\DelegatingContentTreeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Connection controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 * @Route("/node_connection")
 */
class ListController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/list", name="node_connections_list")
     */
    public function indexAction(Request $request)
    {
        $nodeId = $request->get('tid');
        $language = $request->get('language');

        /** @var $connectionService NodeConnectionService */
        $connectionService = $this->get('phlexible_node_connection.node_connection_service');

        /** @var $treeManager ContentTreeManagerInterface */
        $treeManager = $this->get('phlexible_tree.content_tree_manager');

        /** @var $types ConnectionTypeCollection */
        $types = $this->get('phlexible_node_connection.connection_types');

        $connections = $connectionService->findByNodeId($nodeId);

        $result = array();
        foreach ($connections as $connection) {
            if ($nodeId === $connection->getSourceNodeId()) {
                $sourceNodeId = $connection->getSourceNodeId();
                $targetNodeId = $connection->getTargetNodeId();
                $sort = $connection->getSourceSort();
                $origin = 'outbound';
            } else {
                $sourceNodeId = $connection->getTargetNodeId();
                $targetNodeId = $connection->getSourceNodeId();
                $sort = $connection->getTargetSort();
                $origin = 'inbound';
            }

            try {
                $sourceNode = $treeManager->findByTreeId($sourceNodeId)->get($sourceNodeId);
                $targetNode = $treeManager->findByTreeId($targetNodeId)->get($targetNodeId);
            } catch (\Exception $e) {
                continue;
            }

            $type = $types->get($connection->getType());

            $result[] = array(
                'id'         => $connection->getId(),
                'new'        => 0,
                'type'       => $type->getKey(),
                'iconCls'    => $type->getIconClass($connection->origin),
                'typeText'   => $type->getTitle($connection->origin, $language),
                'origin'     => $origin,
                'source'     => $sourceNodeId,
                'target'     => $targetNodeId,
                'targetText' => $targetNode->getTitle(),
                'sort'       => $sort
            );
        }

        foreach ($types as $type) {
            $allowedSourceElementTypeIds = $type->getAllowedElementTypeIds('source');

            if (!count($allowedSourceElementTypeIds) || in_array($sourceElementTypeId, $allowedSourceElementTypeIds)) {
                $types[$type->getKey() . '_source'] = array(
                    'key' => $type->getKey(),
                    'type' => $type->getType(),
                    'origin' => 'source',
                    'title' => $type->getSourceTitle(),
                    'text' => $type->getSourceText(),
                    'allowedElementTypeIds' => $type->getAllowedTargetElementTypeIds(),
                );
            }

            $allowedElementTypeIdsTarget = $type->getAllowedElementTypeIds('target');

            if (!count($allowedElementTypeIdsTarget) || in_array($sourceElementTypeId, $allowedElementTypeIdsTarget)) {
                $types[$type->getKey() . '_target'] = array(
                    'key' => $type->getKey(),
                    'type' => $type->getType(),
                    'origin' => 'target',
                    'title' => $type->getTargetTitle(),
                    'text' => $type->getTargetText(),
                    'allowedElementTypeIds' => $type->getAllowedSourceElementTypeIds(),
                );
            }
        }

        return new JsonResponse(
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
