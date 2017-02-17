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

use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Phlexible\Bundle\NodeConnectionBundle\Model\NodeConnectionManagerInterface;

/**
 * Node listener
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class NodeListener
{
    /**
     * @var NodeConnectionManagerInterface
     */
    private $nodeConnectionManager;

    public function __construct(NodeConnectionManagerInterface $nodeConnectionManager)
    {
        $this->nodeConnectionManager = $nodeConnectionManager;
    }

    public function onSaveNodeData(SaveNodeData $event)
    {
        $node = $event->getNode();
        $data = $event->getData();
        $language = $event->getLanguage();

        if (!isset($data['elementconnections']))
        {
            return;
        }

        $nodeId = $node->getId();
        $data = $data['elementconnections'];

        try
        {
            $connections = $this->nodeConnectionManager->findByNodeId($nodeId);
            $allTypes = $this->nodeConnectionManager->getAllTypes();

            foreach ($data as $row)
            {
                $connection = $this->nodeConnectionManager->find($row['id']);

                if (!$connection) {
                    $connection = new NodeConnection();
                } else {
                    unset($connections[$connection->id]);

                    $connectionChanged = false;
                    if ($connection->type->getKey() !== $row['type'])
                    {
                        $connectionChanged = true;
                    }
                    else
                    {
                        if ($connection->origin === $row['origin'])
                        {
                            if ($connection->source != $row['source'])
                            {
                                $connectionChanged = true;
                            }
                            elseif ($connection->target != $row['target'])
                            {
                                $connectionChanged = true;
                            }
                            elseif ($connection->sortSource != $row['sort'])
                            {
                                $connectionChanged = true;
                            }
                        }
                        else
                        {
                            if ($connection->source != $row['target'])
                            {
                                $connectionChanged = true;
                            }
                            elseif ($connection->target != $row['source'])
                            {
                                $connectionChanged = true;
                            }
                            elseif ($connection->sortTarget != $row['sort'])
                            {
                                $connectionChanged = true;
                            }
                        }
                    }

                    if (!$connectionChanged)
                    {
                        continue;
                    }
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
        }
        catch (Exception $e)
        {
            MWF_Log::exception($e);
        }
    }
}
