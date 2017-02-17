<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Tests\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Phlexible\Bundle\NodeConnectionBundle\Doctrine\NodeConnectionManager;
use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Phlexible\Bundle\NodeConnectionBundle\Event\NodeConnectionEvent;
use Phlexible\Bundle\NodeConnectionBundle\NodeConnectionEvents;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Node connection manager test.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class NodeConnectionManagerTest extends TestCase
{
    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var NodeConnectionManager
     */
    private $manager;

    protected function setUp()
    {
        $this->entityRepository = $this->prophesize(EntityRepository::class);
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->entityManager->getRepository(NodeConnection::class)->willReturn($this->entityRepository->reveal());
        $this->eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->manager = new NodeConnectionManager($this->entityManager->reveal(), $this->eventDispatcher->reveal());
    }

    public function testFind()
    {
        $this->entityRepository->find(123)->shouldBeCalled();

        $this->manager->find(123);
    }

    public function testFindOneBy()
    {
        $this->entityRepository->findOneBy(array('id' => 123), array('foo' => 'ASC'))->shouldBeCalled();

        $this->manager->findOneBy(array('id' => 123), array('foo' => 'ASC'));
    }

    public function testFindBy()
    {
        $this->entityRepository->findBy(array('id' => 123), array('foo' => 'ASC'), 10, 20)->shouldBeCalled();

        $this->manager->findBy(array('id' => 123), array('foo' => 'ASC'), 10, 20);
    }

    public function testFindByNodeId()
    {
        $this->entityRepository->findBy(array('sourceNodeId' => 123), array('sourceSort' => 'ASC'))->willReturn(array())->shouldBeCalled();
        $this->entityRepository->findBy(array('targetNodeId' => 123), array('targetSort' => 'ASC'))->willReturn(array())->shouldBeCalled();

        $this->manager->findByNodeId(123);
    }

    public function testUpdateNodeConnection()
    {
        $connection = new NodeConnection();

        $this->eventDispatcher->dispatch(NodeConnectionEvents::BEFORE_UPDATE_NODE_CONNECTION, Argument::type(NodeConnectionEvent::class))->shouldBeCalled()->will(function($args) {
            return $args[1];
        });
        $this->eventDispatcher->dispatch(NodeConnectionEvents::UPDATE_NODE_CONNECTION, Argument::type(NodeConnectionEvent::class))->shouldBeCalled();

        $this->entityManager->persist($connection)->shouldBeCalled();
        $this->entityManager->flush($connection)->shouldBeCalled();

        $this->manager->updateNodeConnection($connection);
    }

    public function testDeleteNodeConnection()
    {
        $connection = new NodeConnection();

        $this->eventDispatcher->dispatch(NodeConnectionEvents::BEFORE_DELETE_NODE_CONNECTION, Argument::type(NodeConnectionEvent::class))->shouldBeCalled()->will(function($args) {
            return $args[1];
        });
        $this->eventDispatcher->dispatch(NodeConnectionEvents::DELETE_NODE_CONNECTION, Argument::type(NodeConnectionEvent::class))->shouldBeCalled();

        $this->entityManager->remove($connection)->shouldBeCalled();
        $this->entityManager->flush($connection)->shouldBeCalled();

        $this->manager->deleteNodeConnection(new NodeConnection());
    }
}
