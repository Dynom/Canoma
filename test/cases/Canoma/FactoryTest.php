<?php
/**
 * @author Mark van der Velden <mark@dynom.nl>
 */ 
class FactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructionOfManager()
    {
        $config = array(
            \Canoma\Factory::CONF_REPLICA_COUNT => 200,
            \Canoma\Factory::CONF_HASHING_ADAPTER => 'Md5'
        );

        $factory = new \Canoma\Factory();
        $manager = $factory->createManager($config);

        $this->assertTrue($manager instanceof \Canoma\Manager);
    }


    /**
     * @dataProvider adapterNameProvider
     */
    public function testConstructionOfAdapter($adapterName)
    {
        // This should be the name.
        $fqn = '\Canoma\HashAdapter\\'. $adapterName;


        // Specifying the adapter name in the configuration
        $config = array(
            'hashing_adapter' => $adapterName
        );

        // Create the adapter, based on the configuration
        $factory = new \Canoma\Factory();
        $adapter = $factory->createAdapter($config);

        $this->assertInstanceOf($fqn, $adapter, 'Expecting the adapter to be an instance of "'. $fqn .'"');
        $this->assertInstanceOf('\Canoma\HashAdapterInterface', $adapter, 'Expecting the adapter to implement the hash adapter interface');
    }


    /**
     * Provides adapter names.
     *
     * @return array
     */
    public function adapterNameProvider()
    {
        return array(
            array('Md5'),
            array('Crc32'),
        );
    }
}