<?php
/**
 * @author Mark van der Velden <mvdvelden@ibuildings.nl>
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Canoma\Manager
     */
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
    public function testAddNodeDuplicateFail()
    {
        $this->manager->addNode('foo');
        $this->assertEquals(1, count($this->manager->getAllNodes()));

        // This should throw an exception
        $this->manager->addNode('foo');
        $this->fail('Expecting exactly 1 node after adding 1');
    }


    /**
     * Adding invalid node names should fail.
     *
     * @expectedException \RuntimeException
     */
    public function testAddNodeInvalidNameFail()
    {
        // This should throw an exception
        $this->manager->addNode(2);
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
     * Expecting the replica count to determine the amount of positions that are generated
     * @expectedException \RuntimeException
     */
    public function testNodePositionsForInvalidNode()
    {
        // This should throw an exception
        $this->manager->getPositionsOfNode('f-o-o b-a-r');
        $this->fail('Expecting an exception to be thrown when positions of an unexisting node is requested.');
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
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Md5(),
            150
        );
        $manager->addNode('A');
        $manager->addNode('B');
        $manager->addNode('C');
        $manager->addNode('D');

        $cacheIdentifier = 'user:42';

        $node = $manager->getNodeForString($cacheIdentifier);
        $this->assertInternalType('string', $node, 'Expecting the node to be a string');
    }


    /**
     * @dataProvider deviationParameterProvider
     *
     * @param int $replicates
     * @param int $nodes
     * @param int $keyCount
     * @param int $expectedSD
     */
    public function testCorrectDeviations($replicates = 37, $nodes = 2, $keyCount = 100, $expectedSD = 4)
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Md5(),
            $replicates
        );

        // Adding the amount of nodes
        for ($i = 0; $i < $nodes; $i++) {
            $manager->addNode('Node '. $i);
        }

        // Do lookups for the amount of cache-keys
        $result = array();
        for ($i = 0; $i < $keyCount; $i++) {
            $result[] = $manager->getNodeForString("user:". $i);
        }


        $standardDeviation = $this->calculateSDFromResult($result);
        $this->assertEquals($expectedSD, (int) $standardDeviation, 'Expecting the standard deviation for these parameters to be '. $expectedSD);
    }

    /**
     * @return array replicates, nodes, keyCount, expectedSD
     */
    public function deviationParameterProvider()
    {
        return array(
            array(37, 2, 100, 3),
            array(10, 10, 100, 4),
            array(20, 3, 1000, 18),
        );
    }


    /**
     * Helper method, calculating the standard deviation of a list of nodes
     *
     * @param array $result
     * @return float
     */
    private function calculateSDFromResult(array $result)
    {
        $resultSummary = array_count_values($result);
        $mean = array_sum($resultSummary) / count($resultSummary);

        $deviationResult = array();
        foreach ($resultSummary as $node => $nodeCount) {
            $deviationResult[] = pow($nodeCount - $mean, 2);
        }

        return sqrt(array_sum($deviationResult) / count($deviationResult));
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


    /**
     * Testing the 'getAdapter' functionality
     */
    public function testGetAdapter()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Crc32(),
            500
        );

        $this->assertInstanceOf('\Canoma\HashAdapter\Crc32', $manager->getAdapter());
    }


    public function testAddNodes()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Crc32(),
            50
        );

        $manager->addNodes(
            array(
                 'a',
                 'b'
            )
        );

        $this->assertCount(2, $manager->getAllNodes(), 'Expecting two nodes, after adding two.');

        $manager->addNodes(array('c'))
                ->addNodes(array('d'));

        $this->assertCount(4, $manager->getAllNodes(), 'Expecting four nodes, after adding two more.');
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testAddNodesDuplicate()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Crc32(),
            50
        );

        $manager->addNodes(
            array(
                 'a',
                 'b'
            )
        );

        $this->assertCount(2, $manager->getAllNodes(), 'Expecting two nodes, after adding two.');

        $manager->addNodes(array('a'));

        $this->fail('Expecting an exception being thrown after adding a duplicate node.');
    }
}
