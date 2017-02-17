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

use Exception;
use Phlexible\Bundle\ElementBundle\ElementService;
use Phlexible\Bundle\NodeConnectionBundle\ConnectionType\ConnectionTypeCollection;
use Phlexible\Bundle\NodeConnectionBundle\ConnectionType\ConnectionTypeInterface;
use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Phlexible\Bundle\NodeConnectionBundle\Model\NodeConnectionManagerInterface;
use Phlexible\Bundle\TreeBundle\ContentTree\ContentTreeManagerInterface;
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

        /** @var $connectionManager NodeConnectionManagerInterface */
        $connectionManager = $this->get('phlexible_node_connection.node_connection_manager');

        /** @var $treeManager ContentTreeManagerInterface */
        $treeManager = $this->get('phlexible_tree.content_tree_manager');

        /** @var $elementService ElementService */
        $elementService = $this->get('phlexible_element.element_service');

        /** @var $types ConnectionTypeCollection */
        $types = $this->get('phlexible_node_connection.connection_types');

        $connections = $connectionManager->findByNodeId($nodeId);

        $node = $treeManager->findByTreeId($nodeId)->get($nodeId);
        $element = $elementService->findElement($node->getTypeId());
        $elementtype = $elementService->findElementtype($element);
        $elementtypeId = $elementtype->getId();

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
                $targetNode = $treeManager->findByTreeId($targetNodeId)->get($targetNodeId);
            } catch (Exception $e) {
                continue;
            }

            $type = $types->get($connection->getType());

            $result[] = array(
                'id'         => $connection->getId(),
                'new'        => 0,
                'type'       => $type->getKey(),
                'iconCls'    => $origin === 'outbound' ? $type->getTargetIconClass() : $type->getSourceIconClass(),
                'typeText'   => $origin === 'outbound' ? $type->getTargetTitle() : $type->getSourceTitle(),
                'origin'     => $origin,
                'source'     => $sourceNodeId,
                'target'     => $targetNodeId,
                'targetText' => $targetNode->getTitle(),
                'sort'       => $sort
            );
        }

        foreach ($types as $type) {
            $allowedSourceElementTypeIds = $type->getAllowedElementTypeIds('source');

            if (!count($allowedSourceElementTypeIds) || in_array($elementtypeId, $allowedSourceElementTypeIds)) {
                $types[$type->getKey() . '_source'] = array(
                    'key' => $type->getKey(),
                    'type' => $type->getType(),
                    'origin' => 'source',
                    'title' => $type->getSourceTitle(),
                    'text' => $type->getSourceText(),
                    'iconClass' => $type->getSourceIconClass(),
                    'allowedElementTypeIds' => $type->getAllowedTargetElementTypeIds(),
                );
            }

            $allowedElementTypeIdsTarget = $type->getAllowedElementTypeIds('target');

            if (!count($allowedElementTypeIdsTarget) || in_array($elementtypeId, $allowedElementTypeIdsTarget)) {
                $types[$type->getKey() . '_target'] = array(
                    'key' => $type->getKey(),
                    'type' => $type->getType(),
                    'origin' => 'target',
                    'title' => $type->getTargetTitle(),
                    'text' => $type->getTargetText(),
                    'iconClass' => $type->getTargetIconClass(),
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

    /**
     * @param Request $request
     * @return Response
     * @Route("/save", name="node_connections_save")
     */
    public function saveAction(Request $request)
    {
        $nodeId = $request->get('tid');
        $data = $request->get('data');
        $data = json_decode($data);

        /** @var $connectionManager NodeConnectionManagerInterface */
        $connectionManager = $this->get('phlexible_node_connection.node_connection_manager');

        $connections = $connectionManager->findByNodeId($nodeId);

        foreach ($data as $row) {
            $connection = $connectionManager->find($row['id']);

            if (!$connection) {
                $connection = new NodeConnection();
            } else {
                unset($connections[$connection->getId()]);
            }

            $connection->setType($row['type']);
            $origin = $row['origin'];
            if ($origin === ConnectionTypeInterface::ORIGIN_SOURCE)
            {
                $connection->setSourceNodeId((int) $row['source']);
                $connection->setTargetNodeId((int) $row['target']);
                $connection->setSourceSort((int) $row['sort']);
            } else {
                $connection->setSourceNodeId((int) $row['target']);
                $connection->setTargetNodeId((int) $row['source']);
                $connection->setTargetSort((int) $row['sort']);
            }

            $connectionManager->updateNodeConnection($connection);
        }

        foreach ($connections as $connection) {
            $connectionManager->deleteNodeConnection($connection);
        }

        return new JsonResponse();
    }
}
