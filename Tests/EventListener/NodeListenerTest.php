<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\Tests\EventListener;

use Phlexible\Bundle\ElementBundle\ElementEvents;
use Phlexible\Bundle\ElementBundle\Event\SaveNodeDataEvent;
use Phlexible\Bundle\NodeConnectionBundle\Doctrine\NodeConnectionManager;
use Phlexible\Bundle\NodeConnectionBundle\Entity\NodeConnection;
use Phlexible\Bundle\NodeConnectionBundle\EventListener\NodeListener;
use Phlexible\Bundle\TreeBundle\Entity\TreeNode;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * Node listener test.
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
class NodeListenerTest extends TestCase
{
    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            array(
                ElementEvents::SAVE_NODE_DATA => 'onSaveNodeData',
            ),
            NodeListener::getSubscribedEvents()
        );
    }

    public function testOnSaveNodeData()
    {
        $request = new Request(
            array(),
            array(
                'nodeconnections' => json_encode(
                    array(
                        array(
                            'id'     => 123,
                            'type'   => 'foo',
                            'source' => 10,
                            'target' => 100,
                            'sort'   => 99,
                        ),
                        array(
                            'id'     => 234,
                            'type'   => 'bar',
                            'source' => 20,
                            'target' => 200,
                            'sort'   => 88,
                        ),
                    )
                )
            )
        );

        $treeNode = $this->prophesize(TreeNode::class);
        $treeNode->getId()->willReturn(10);

        $event = new SaveNodeDataEvent(
            $treeNode->reveal(),
            'de',
            $request
        );

        $existingDeleteNode = new NodeConnection();
        $existingDeleteNode->setId(345);

        $existingUpdateNode = new NodeConnection();
        $existingUpdateNode->setId(234);

        $nodeConnectionManager = $this->prophesize(NodeConnectionManager::class);
        $nodeConnectionManager->findByNodeId(10)->willReturn(array($existingUpdateNode, $existingDeleteNode));
        $nodeConnectionManager->find(123)->willReturn(null);
        $nodeConnectionManager->find(234)->willReturn($existingUpdateNode);
        $nodeConnectionManager->updateNodeConnection(
            Argument::that(
                function ($connection) {
                    return $connection->getType() === 'foo';
                }
            )
        )->shouldBeCalled();
        $nodeConnectionManager->updateNodeConnection($existingUpdateNode)->shouldBeCalled();
        $nodeConnectionManager->deleteNodeConnection($existingDeleteNode)->shouldBeCalled();

        $nodeListener = new NodeListener($nodeConnectionManager->reveal());
        $nodeListener->onSaveNodeData($event);
    }
}
