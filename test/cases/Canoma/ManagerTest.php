<?php
/**
 * @author Mark van der Velden <mvdvelden@ibuildings.nl>
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    private $manager;

    public function setUp()
    {
        $this->manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Md5(),
            42
        );
    }


    public function testAddNode()
    {
        $this->assertEquals(0, count($this->manager->getAllNodes()), 'Expecting exactly 0 nodes since none were added.');

        $this->manager->addNode('foo');
        $this->assertEquals(1, count($this->manager->getAllNodes()), 'Expecting exactly 1 node after adding 1');
    }


    /**
     * Adding duplicate nodes should fail.
     *
     * @expectedException \RuntimeException
     */
    public function testAddNodeFail()
    {
        $this->manager->addNode('foo');
        $this->assertEquals(1, count($this->manager->getAllNodes()));

        // This should throw an exception
        $this->manager->addNode('foo');
        $this->fail('Expecting exactly 1 node after adding 1');
    }


    /**
     * Expecting the replica count to determine the amount of positions that are generated
     */
    public function testNodePositionsForSingleNode()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Md5(),
            42
        );

        $manager->addNode('foo');
        $this->assertEquals(42, count($manager->getPositionsOfNode('foo')), 'Expecting 42 positions');
    }


    /**
     * Expecting each position to raise the total amount of positions, and without node collisions
     */
    public function testNodePositions()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Md5(),
            12
        );

        $manager->addNode('foo');
        $this->assertEquals(12, count($manager->getAllPositions()), 'Expecting 12 positions');

        $manager->addNode('1foo');
        $this->assertEquals(24, count($manager->getAllPositions()), 'Expecting 24 positions');

        $manager->addNode('foo1');
        $this->assertEquals(36, count($manager->getAllPositions()), 'Expecting 36 positions');
    }


    public function testGetNodeForString()
    {
        $this->manager->addNode('A');
        $this->manager->addNode('B');
        $this->manager->addNode('C');
        $this->manager->addNode('D');

        $cacheIdentifier = 'user:42';

        $node = $this->manager->getNodeForString($cacheIdentifier);
        $this->assertInternalType('string', $node, 'Expecting the node to be a string');
    }


    /**
     * Testing 100 nodes with 500 replica's
     * 
     * @group performanceTest
     */
    public function testManyNodePositions()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Crc32(),
            500
        );

        for ($i = 0; $i < 100; $i++) {
            $manager->addNode('10.2.2.'. $i .':1142');
        }

        $this->assertEquals(500*100, count($manager->getAllPositions()), 'Expecting 50.000 positions.');
    }
}
