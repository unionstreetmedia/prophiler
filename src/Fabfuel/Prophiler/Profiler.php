<?php
/**
 * @author @fabfuel <fabian@fabfuel.de>
 * @created 13.11.14, 07:47
 */
namespace Fabfuel\Prophiler;

use Fabfuel\Prophiler\Benchmark\BenchmarkFactory;
use Fabfuel\Prophiler\Benchmark\BenchmarkInterface;
use Fabfuel\Prophiler\Exception\UnknownBenchmarkException;

class Profiler implements ProfilerInterface, \Countable
{
    /**
     * @var BenchmarkInterface[]
     */
    protected $benchmarks = array();

    /**
     * @var double Starting time
     */
    protected $start;

    /**
     * Save start time on construction
     */
    public function __construct()
    {
        $this->start = (double)microtime(true);
    }

    /**
     * Start a new benchmark
     *
     * @param string $name Unique identifier like e.g. Class::Method (\Foobar\MyClass::doSomething)
     * @param array $metadata Additional metadata
     * @param string $component Name of the component which triggered the benchmark, e.g. "App", "Database"
     * @return BenchmarkInterface The started benchmark
     */
    public function start($name, array $metadata = array(), $component = null)
    {
        $benchmark = BenchmarkFactory::getBenchmark(
            $name,
            $metadata,
            $component
        );
        $benchmark->start();
        $this->addBenchmark($benchmark);
        return $benchmark;
    }

    /**
     * Stop a running benchmark
     * If no benchmark provided, the last started benchmark is stopped
     *
     * @param BenchmarkInterface $benchmark Benchmark identifier
     * @param array $metadata Additional metadata
     * @throws UnknownBenchmarkException
     * @return BenchmarkInterface $benchmark
     */
    public function stop(BenchmarkInterface $benchmark = null, array $metadata = array())
    {
        if (is_null($benchmark)) {
            $benchmark = $this->getLastBenchmark();
        }
        if (!isset($this->benchmarks[spl_object_hash($benchmark)])) {
            throw new UnknownBenchmarkException('Benchmark not present in profiler');
        }
        $benchmark->stop();
        $benchmark->addMetadata($metadata);
        return $benchmark;
    }

    /**
     * @param BenchmarkInterface $benchmark
     * @return $this
     */
    public function addBenchmark(BenchmarkInterface $benchmark)
    {
        $identifier = spl_object_hash($benchmark);
        $this->benchmarks[$identifier] = $benchmark;
        return $this;
    }

    /**
     * Get the total number of elapsed time in milliseconds
     *
     * @return double Total number of elapsed milliseconds
     */
    public function getDuration()
    {
        if ($this->count()) {
            return $this->getLastBenchmark()->getEndTime() - $this->getStartTime();
        }
        return microtime(true) - $this->getStartTime();
    }

    /**
     * Get the start of the profiler in microtime
     *
     * @return double Timestamp in microtime
     */
    public function getStartTime()
    {
        return $this->start;
    }

    /**
     * Return all measured benchmarks
     *
     * @return BenchmarkInterface[]
     */
    public function getBenchmarks()
    {
        return $this->benchmarks;
    }

    /**
     * @return BenchmarkInterface
     * @throws UnknownBenchmarkException
     */
    public function getLastBenchmark()
    {
        $lastBenchmarkSlice = array_slice($this->benchmarks, -1, 1, true);
        $lastBenchmark = current($lastBenchmarkSlice);
        if ($lastBenchmark) {
            return $lastBenchmark;
        }
        throw new UnknownBenchmarkException('No benchmarks to return last one');
    }

    /**
     * Get the total number of benchmarks
     *
     * @return int Total number of benchmarks
     */
    public function count()
    {
        return count($this->benchmarks);
    }

    /**
     * Return the current benchmark
     *
     * @return BenchmarkInterface|bool
     */
    public function current()
    {
        return current($this->benchmarks);
    }

    /**
     * Move forward to next benchmark
     *
     * @return void
     */
    public function next()
    {
        next($this->benchmarks);
    }

    /**
     * Return the key of the current Benchmark
     *
     * @return string
     */
    public function key()
    {
        return key($this->benchmarks);
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        $key = key($this->benchmarks);

        if ($key === false || $key === null) {
            return false;
        }

        return array_key_exists($key, $this->benchmarks);
    }

    /**
     * Rewind the Iterator to the first benchmark
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->benchmarks);
    }
}
