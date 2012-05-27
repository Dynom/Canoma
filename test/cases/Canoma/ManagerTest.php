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
}
