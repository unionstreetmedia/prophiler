<?php
/**
 * @author @fabfuel <fabian@fabfuel.de>
 * @created 13.11.14, 07:52 
 */
namespace Fabfuel\Prophiler;

use Fabfuel\Prophiler\Benchmark\BenchmarkInterface;

interface ProfilerInterface extends \Iterator
{
    /**
     * Start a new benchmark
     *
     * @param string $name Unique identifier like e.g. Class::Method (\Foobar\MyClass::doSomething)
     * @param array $metadata Addtional metadata or data
     * @param string $component Name of the component which triggered the benchmark, e.g. "App", "Database"
     * @return BenchmarkInterface The started benchmark
     */
    public function start($name, array $metadata = array(), $component = null);

    /**
     * Stop a running benchmark
     * If no benchmark provided, the last started benchmark is stopped
     *
     * @param BenchmarkInterface $benchmark A previously benchmark
     * @param array $metadata Addtional metadata or data
     * @return BenchmarkInterface
     */
    public function stop(BenchmarkInterface $benchmark = null, array $metadata = array());

    /**
     * Get the total number of elapsed time in milliseconds
     *
     * @return double Total number of elapsed milliseconds
     */
    public function getDuration();

    /**
     * Get the start of the profiler in microtime
     *
     * @return double Timestamp in microtime
     */
    public function getStartTime();
}
