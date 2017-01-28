<?php
	use Kramework\Utils\Timing\Benchmark;

	require_once("src/Utils/Timing/Benchmark.php");

	class BenchmarkTest extends \PHPUnit_Framework_TestCase
	{
		public function testBenchmark() {
			$benchmark = new class (2000) extends Benchmark {
				/**
				 * Overwrite and include the code to benchmark inside this function.
				 */
				public function runCycle() {
					usleep(1000);
				}
			};

			$results = $benchmark->runTest();
			$this->assertGreaterThanOrEqual(2, $results->executionTime, "Benchmark didn't run for expected forced time.");
		}
	}