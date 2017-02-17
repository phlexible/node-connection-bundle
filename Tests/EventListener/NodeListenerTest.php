<?php

namespace Phlexible\Bundle\NodeConnectionBundle\Tests\EventListener;

use Phlexible\Bundle\NodeConnectionBundle\Doctrine\NodeConnectionManager;
use Phlexible\Bundle\NodeConnectionBundle\EventListener\NodeListener;
use Phlexible\Bundle\TreeBundle\Entity\TreeNode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Phlexible\Bundle\ElementBundle\Event\SaveNodeDataEvent;
use Phlexible\Bundle\ElementBundle\ElementEvents;

class NodeListenerTest extends TestCase
{
    private $connection;

    public function setUp()
    {
        $this->connections = [];
    }

    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            array(
                ElementEvents::SAVE_NODE_DATA => 'onSaveNodeData',
            ),
            NodeListener::getSubscribedEvents()
        );
    }

//    public function testOnSaveNodeData()
//    {
//        $request = new Request();
//
//        /**
//         * @var TreeNode $treeNode
//         */
//        $treeNode = $this->prophesize(TreeNode::class);
//        $treeNode->getId()->willReturn(10);
//
//        $event = new SaveNodeDataEvent(
//            $treeNode,
//            'de',
//            $request
//        );
//
//
//
//        $nodeConnectionManager = $this->prophesize(NodeConnectionManager::class);
//        $nodeConnectionManager->findByNodeId()->willReturn();
//
//        $nodeListener = new NodeListener($nodeConnectionManager);
//
//
//    }
}
