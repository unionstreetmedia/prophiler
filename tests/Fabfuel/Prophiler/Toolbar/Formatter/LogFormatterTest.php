<?php
/**
 * @author @fabfuel <fabian@fabfuel.de>
 * @created 17.11.14, 08:26
 */
namespace Fabfuel\Prophiler\Toolbar\Formatter;

use Fabfuel\Prophiler\Benchmark\BenchmarkInterface;

class LogFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BenchmarkFormatter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $formatter;

    /**
     * @var BenchmarkInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $benchmark;

    public function setUp()
    {
        $this->benchmark = $this->getMock('Fabfuel\Prophiler\Benchmark\BenchmarkInterface');

        $this->formatter = new LogFormatter();
        $this->formatter->setBenchmark($this->benchmark);
    }

    /**
     * @param string $severity
     * @param string $colorClass
     * @dataProvider getColorClasses
     */
    public function testGetColorClass($severity, $colorClass)
    {
        $this->benchmark->expects($this->any())
            ->method('getMetadata')
            ->willReturn(array('severity' => $severity));

        $this->assertSame($colorClass, $this->formatter->getColorClass());
    }

    /**
     * @return array
     */
    public function getColorClasses()
    {
        return array(
            array('A', 'severity-A'),
            array('AB', 'severity-AB'),
            array('ABC', 'severity-ABC'),
        );
    }

    /**
     * @param string $severity
     * @param string $colorClass
     * @dataProvider getLabels
     */
    public function testGetLabels($severity, $colorClass)
    {
        $this->benchmark->expects($this->any())
            ->method('getMetadata')
            ->willReturn(array('severity' => $severity));

        $this->assertSame($colorClass, $this->formatter->getLabel());
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return array(
            array('A', '<span class="label severity-A">A</span>'),
            array('AB', '<span class="label severity-AB">AB</span>'),
            array('ABC', '<span class="label severity-ABC">ABC</span>'),
        );
    }
}
