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
use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Phlexible\Bundle\NodeConnectionBundle\Event\NodeConnectionEvent;
use Phlexible\Bundle\NodeConnectionBundle\Model\NodeConnectionManagerInterface;
use Phlexible\Bundle\NodeConnectionBundle\NodeConnectionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Node connection manager.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class NodeConnectionManager implements NodeConnectionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return EntityRepository
     */
    private function getNodeConnectionRepository()
    {
        if ($this->repository === null) {
            $this->repository = $this->entityManager->getRepository(NodeConnection::class);
        }

        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return $this->getNodeConnectionRepository()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->getNodeConnectionRepository()->findOneBy($criteria, $orderBy);
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getNodeConnectionRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param int $nodeId
     *
     * @return NodeConnection[]
     */
    public function findByNodeId($nodeId)
    {
        $connections = array();

        foreach ($this->getNodeConnectionRepository()->findBy(['sourceNodeId' => $nodeId], ['sourceSort' => 'ASC']) as $connection) {
            $connections[] = $connection;
        }

        foreach ($this->getNodeConnectionRepository()->findBy(['targetNodeId' => $nodeId], ['targetSort' => 'ASC']) as $connection) {
            $connections[] = $connection;
        }

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
