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


    /**
     * @dataProvider invalidManagerConfigurationProvider
     * @expectedException \InvalidArgumentException
     * @param array $config
     */
    public function testCreateManagerInvalidConfiguration(array $config)
    {
        $factory = new \Canoma\Factory();

        // This should thrown an exception
        $factory->createManager(
            $config
        );

        $this->fail('Expected exceptions on invalid manager configuration.');
    }


    /**
     * @return array Invalid replica count values
     */
    public function invalidManagerConfigurationProvider()
    {
        return array(
            array(array()),
            array(array(\Canoma\Factory::CONF_REPLICA_COUNT => 'foo')),
            array(array(\Canoma\Factory::CONF_REPLICA_COUNT => -1)),
        );
    }


    /**
     * @dataProvider invalidAdapterConfigurationProvider
     * @expectedException \InvalidArgumentException
     * @param array $config
     */
    public function testCreateAdapterInvalidConfiguration(array $config)
    {
        $factory = new \Canoma\Factory();

        // This should thrown an exception
        $factory->createAdapter(
            $config
        );

        $this->fail('Expected exceptions on invalid adapter configuration.');
    }


    /**
     * @return array Invalid replica count values
     */
    public function invalidAdapterConfigurationProvider()
    {
        return array(
            array(array()), // Undefined key
        );
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testCreateAdapterInvalidConfigurationClass()
    {
        $factory = new \Canoma\Factory();

        // This should thrown an exception
        $factory->createAdapter(
            array(\Canoma\Factory::CONF_HASHING_ADAPTER => 'someUnexistingClassIsBeingPassedAlongHere')
        );

        $this->fail('Expected exceptions on invalid adapter definition, the class does not exist.');
    }
}