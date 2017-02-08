<?php
	require_once(__DIR__ . "/../src/Timing/Benchmarking/Benchmark.php");

	use Kramework\Timing\Benchmarking\Benchmark;

	class BenchmarkTest extends \PHPUnit_Framework_TestCase
	{
		public function testBenchmark() {
			$benchmark = new class (20, 100) extends Benchmark {
				/**
				 * Overwrite and include the code to benchmark inside this function.
				 */
				public function execute() {
					usleep(1000);
				}
			};

			$result = $benchmark->runTest();
			$this->assertGreaterThanOrEqual(2, $result->getElapsed(), "Benchmark didn't run for expected forced time.");
			$this->assertEquals('Benchmark1', $result->getName());
		}
	}