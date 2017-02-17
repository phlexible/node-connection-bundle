<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Phlexible\Bundle\NodeConnectionBundle\ConnectionType\ConnectionTypeCollection;
use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Phlexible\Bundle\NodeConnectionBundle\Event\NodeConnectionEvent;
use Phlexible\Bundle\NodeConnectionBundle\Model\NodeConnectionManagerInterface;
use Phlexible\Bundle\NodeConnectionBundle\NodeConnectionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Node connection manager
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class NodeConnectionService
{
    /**
     * @var NodeConnectionManagerInterface
     */
    private $nodeConnectionManager;

    /**
     * @var ConnectionTypeCollection
     */
    private $types;

    /**
     * Constructor
     *
     * @param NodeConnectionManagerInterface $nodeConnectionManager
     * @param ConnectionTypeCollection       $types
     */
    public function __construct(NodeConnectionManagerInterface $nodeConnectionManager, ConnectionTypeCollection $types)
    {
        $this->nodeConnectionManager = $nodeConnectionManager;
        $this->types = $types;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return $this->nodeConnectionManager->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByNodeId($nodeId, $type = null)
    {
        $connections = array();

        $criteria = array('source' => $nodeId);
        if ($type) {
            $criteria['type'] = $type;
        }

        foreach ($this->nodeConnectionManager->findBy($criteria, array('sortSource' => 'ASC')) as $connection) {
            $connections[] = new OriginSourceConnection($connection, $this->types->get($connection->getType()));
        }


        $criteria = array('target' => $nodeId);
        if ($type) {
            $criteria['type'] = $type;
        }

        foreach ($this->nodeConnectionManager->findBy($criteria, array('sortTarget' => 'ASC')) as $connection) {
            $connections[] = new OriginTargetConnection($connection, $this->types->get($connection->getType()));
        }

        /*
        $connection         = new Makeweb_ElementConnections_Connection();
        $connection->id     = Brainbits_Util_Uuid::generate();
        $connection->type   = $types['generic'];
        $connection->source = 1024;
        $connection->target = 1109;
        $connection->origin = 'source';
        $connections[]      = $connection;

        $connection         = new Makeweb_ElementConnections_Connection();
        $connection->id     = Brainbits_Util_Uuid::generate();
        $connection->type   = $types['successor'];
        $connection->source = 1024;
        $connection->target = 1082;
        $connection->origin = 'target';
        $connections[]      = $connection;

        $connection         = new Makeweb_ElementConnections_Connection();
        $connection->id     = Brainbits_Util_Uuid::generate();
        $connection->type   = new Makeweb_ElementConnections_Type_Successor();
        $connection->source = 1082;
        $connection->target = 1024;
        $connection->origin = 'source';
        $connections[]      = $connection;
        */

        return $connections;
    }

    /**
     * {@inheritdoc}
     */
    public function updateNodeConnection(NodeConnection $nodeConnection)
    {
        $beforeEvent = new NodeConnectionEvent($nodeConnection);
        if ($this->eventDispatcher->dispatch(NodeConnectionEvents::BEFORE_UPDATE_NODE_CONNECTION, $beforeEvent)->isPropagationStopped()) {
            return;
        }

        $this->entityManager->persist($nodeConnection);
        $this->entityManager->flush($nodeConnection);

        // post save event
        $event = new NodeConnectionEvent($nodeConnection);
        $this->eventDispatcher->dispatch(NodeConnectionEvents::UPDATE_NODE_CONNECTION, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteNodeConnection(NodeConnection $nodeConnection)
    {
        $beforeEvent = new NodeConnectionEvent($nodeConnection);
        if ($this->eventDispatcher->dispatch(NodeConnectionEvents::BEFORE_DELETE_NODE_CONNECTION, $beforeEvent)->isPropagationStopped()) {
            return false;
        }

        $this->entityManager->remove($nodeConnection);
        $this->entityManager->flush($nodeConnection);

        $event = new NodeConnectionEvent($nodeConnection);
        $this->eventDispatcher->dispatch(NodeConnectionEvents::DELETE_NODE_CONNECTION, $event);
    }
}
