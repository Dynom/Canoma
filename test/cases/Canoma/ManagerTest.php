<?php
/**
 * @author Mark van der Velden <mvdvelden@ibuildings.nl>
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Md5(),
            42
        );

        $this->assertInstanceOf('\Canoma\Manager', $manager);
    }


    public function testAddNode()
    {
        $manager = new \Canoma\Manager(
            new \Canoma\HashAdapter\Md5(),
            42
        );

        $this->assertEquals(0, count($manager->getAllNodes()), 'Expecting exactly 0 nodes since none were added.');

        $manager->addNode('foo');
        $this->assertEquals(1, count($manager->getAllNodes()), 'Expecting exactly 1 node after adding 1');
    }
}
