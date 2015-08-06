<?php
/**
 * @author Fabian Fuelling <fabian@fabfuel.de>
 * @created: 14.11.14 12:13
 */

namespace Fabfuel\Prophiler\Plugin\Phalcon\Db;

use Fabfuel\Prophiler\Plugin\Phalcon\PhalconPluginTest;

class AdapterPluginTest extends PhalconPluginTest
{
    /**
     * @var AdapterPlugin
     */
    protected $adapterPlugin;

    public function setUp()
    {
        parent::setUp();
        $this->adapterPlugin = AdapterPlugin::getInstance($this->profiler, $this->dependencyInjector) ;
    }

    /**
     * @covers Fabfuel\Prophiler\Plugin\Phalcon\Db\AdapterPlugin::beforeQuery
     * @covers Fabfuel\Prophiler\Plugin\Phalcon\Db\AdapterPlugin::afterQuery
     * @covers Fabfuel\Prophiler\Plugin\PluginAbstract
     * @uses Fabfuel\Prophiler\Profiler
     */
    public function testQuery()
    {
        $benchmark = $this->getMock('\Fabfuel\Prophiler\Benchmark\BenchmarkInterface');

        $adapter = $this->getMockBuilder('Phalcon\Db\Adapter')
            ->disableOriginalConstructor()
            ->getMock();

        $adapter->expects($this->once())
            ->method('getSQLStatement')
            ->willReturn('SELECT * FROM foobar;');

        $adapter->expects($this->once())
            ->method('getSQLVariables')
            ->willReturn(array('foo' => 'bar'));

        $adapter->expects($this->once())
            ->method('getSQLBindTypes')
            ->willReturn(array('lorem' => 'ipsum'));

        $adapter->expects($this->once())
            ->method('getDescriptor')
            ->willReturn(array('dbname' => 'foobar'));

        $event = $this->getMockBuilder('Phalcon\Events\Event')
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->exactly(1))
            ->method('getSource')
            ->willReturn($adapter);

        $metadata = array(
            'query' => 'SELECT * FROM foobar;',
            'params' => array('foo' => 'bar'),
            'bindTypes' => array('lorem' => 'ipsum'),
            'database' => 'foobar',
        );

        $this->profiler->expects($this->once())
            ->method('start')
            ->with(get_class($adapter) . '::query', $metadata, 'Database')
            ->willReturn($benchmark);

        $this->profiler->expects($this->once())
            ->method('stop')
            ->with($benchmark);

        $this->adapterPlugin->beforeQuery($event, $adapter);
        $this->adapterPlugin->afterQuery($event, $adapter);
    }
}
