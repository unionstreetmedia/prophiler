<?php
/**
 * @author @fabfuel <fabian@fabfuel.de>
 * @created 17.11.14, 08:26
 */
namespace Fabfuel\Prophiler\Toolbar\Formatter;

use Fabfuel\Prophiler\Benchmark\BenchmarkInterface;

class BenchmarkFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BenchmarkFormatter
     */
    protected $formatter;

    /**
     * @var BenchmarkInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $benchmark;

    public function setUp()
    {
        $this->benchmark = $this->getMock('Fabfuel\Prophiler\Benchmark\BenchmarkInterface');

        $this->formatter = new BenchmarkFormatter();
        $this->formatter->setBenchmark($this->benchmark);
    }

    public function testGetId()
    {
        $this->assertSame(spl_object_hash($this->benchmark), $this->formatter->getId());
    }

    /**
     * @dataProvider formatterProvider
     */
    public function testIdsAreUnique($benchmark)
    {
        $lastId = $this->formatter->getId();

        $this->formatter->setBenchmark($benchmark);
        $this->assertNotSame($lastId, $this->formatter->getId());
    }

    public function formatterProvider()
    {
        return array(
            array($this->getMock('Fabfuel\Prophiler\Benchmark\BenchmarkInterface')),
            array($this->getMock('Fabfuel\Prophiler\Benchmark\BenchmarkInterface'))
        );
    }

    public function testGetName()
    {
        $this->benchmark->expects($this->any())
            ->method('getName')
            ->willReturn('Foobar');

        $this->assertSame('Foobar', $this->formatter->getName());
    }

    public function testGetComponent()
    {
        $this->benchmark->expects($this->any())
            ->method('getComponent')
            ->willReturn('Foobar');

        $this->assertSame('Foobar', $this->formatter->getComponent());
    }

    public function testGetMemoryUsage()
    {
        $this->benchmark->expects($this->any())
            ->method('getMemoryUsage')
            ->willReturn(1234567);

        $this->assertSame('1.177 MB', $this->formatter->getMemoryUsage());
    }

    public function testGetDuration()
    {
        $this->benchmark->expects($this->any())
            ->method('getDuration')
            ->willReturn(0.012345);

        $this->assertSame('12.35 ms', $this->formatter->getDuration());
    }

    /**
     * @param string $component
     * @param string $icon
     * @dataProvider getIcons
     */
    public function testGetIcon($component, $icon)
    {
        $this->benchmark->expects($this->any())
            ->method('getComponent')
            ->willReturn($component);

        $this->assertSame($icon, $this->formatter->getIcon());
    }

    /**
     * @return array
     */
    public function getIcons()
    {
        return array(
            array('MongoDB', 'leaf'),
            array('Foobar', 'cog'),
            array('Lorem Ipsum', 'cog'),
        );
    }

    /**
     * @param string $component
     * @param string $colorClass
     * @dataProvider getColorClasses
     */
    public function testGetColorClass($component, $colorClass)
    {
        $this->benchmark->expects($this->any())
            ->method('getComponent')
            ->willReturn($component);

        $this->assertSame($colorClass, $this->formatter->getColorClass());
    }

    /**
     * @return array
     */
    public function getColorClasses()
    {
        return array(
            array('A', 'color-1'),
            array('AB', 'color-2'),
            array('ABC', 'color-3'),
            array('ABCD', 'color-4'),
            array('ABCDE', 'color-0'),
        );
    }

    public function testGetStartTime()
    {
        $time = microtime(true);

        $this->benchmark->expects($this->any())
            ->method('getStartTime')
            ->willReturn($time);

        $this->assertSame($time, $this->formatter->getStartTime());
    }

    public function testGetEndTime()
    {
        $time = microtime(true);

        $this->benchmark->expects($this->any())
            ->method('getEndTime')
            ->willReturn($time);

        $this->assertSame($time, $this->formatter->getEndTime());
    }

    public function testGetMetadata()
    {
        $this->benchmark->expects($this->any())
            ->method('getMetadata')
            ->willReturn(array('lorem' => 'ipsum'));

        $this->assertSame(array('lorem' => 'ipsum'), $this->formatter->getMetadata());
    }
}
