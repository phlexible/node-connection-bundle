<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\EventListener;

use Phlexible\Bundle\ElementBundle\ElementEvents;
use Phlexible\Bundle\ElementBundle\Event\SaveNodeDataEvent;
use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Phlexible\Bundle\NodeConnectionBundle\Model\NodeConnectionManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Node listener.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class NodeListener implements EventSubscriberInterface
{
    /**
     * @var NodeConnectionManagerInterface
     */
    private $nodeConnectionManager;

    public function __construct(NodeConnectionManagerInterface $nodeConnectionManager)
    {
        $this->nodeConnectionManager = $nodeConnectionManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ElementEvents::SAVE_NODE_DATA => 'onSaveNodeData',
        );
    }

    /**
     * @param SaveNodeDataEvent $event
     */
    public function onSaveNodeData(SaveNodeDataEvent $event)
    {
        $node = $event->getNode();
        $language = $event->getLanguage();
        $request = $event->getRequest();

        if (!$request->request->has('nodeconnections')) {
            return;
        }

        $nodeId = $node->getId();
        $data = $request->request->get('nodeconnections');

        $connections = $this->nodeConnectionManager->findByNodeId($nodeId);

        foreach ($data as $row) {
            $connection = $this->nodeConnectionManager->find($row['id']);

            if (!$connection) {
                $connection = new NodeConnection();
            } else {
                foreach ($connections as $index => $existingConnection) {
                    if ($existingConnection->getId() === $connection->getId()) {
                        unset($connections['id']);
                    }
                }

                $connectionChanged = false;
                if ($connection->getType() !== $row['type']) {
                    $connectionChanged = true;
                } else {
                    if ($connection->getSourceNodeId() === $nodeId) {
                        if ($connection->getSourceNodeId() !== $row['source']) {
                            $connectionChanged = true;
                        } elseif ($connection->getTargetNodeId() !== $row['target']) {
                            $connectionChanged = true;
                        } elseif ($connection->getSourceSort() !== $row['sort']) {
                            $connectionChanged = true;
                        }
                    } else {
                        if ($connection->getSourceNodeId() !== $row['target']) {
                            $connectionChanged = true;
                        } elseif ($connection->getTargetNodeId() !== $row['source']) {
                            $connectionChanged = true;
                        } elseif ($connection->getTargetSort() !== $row['sort']) {
                            $connectionChanged = true;
                        }
                    }
                }

                if (!$connectionChanged) {
                    continue;
                }
            }

            $connection->setType($row['type']);
            if ($connection->getSourceNodeId() === $nodeId) {
                $connection->setSourceNodeId((int) $row['source']);
                $connection->setTargetNodeId((int) $row['target']);
                $connection->setSourceSort((int) $row['sort']);
            } else {
                $connection->setSourceNodeId((int) $row['target']);
                $connection->setTargetNodeId((int) $row['source']);
                $connection->setTargetSort((int) $row['sort']);
            }

            $this->nodeConnectionManager->updateNodeConnection($connection);
        }

        foreach ($connections as $connection) {
            $this->nodeConnectionManager->deleteNodeConnection($connection);
        }
    }
}
