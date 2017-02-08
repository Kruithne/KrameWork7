<?php
	/*
	 * Copyright (c) 2017 Kruithne (kruithne@gmail.com)
	 * https://github.com/Kruithne/KrameWork7
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

	namespace Kramework\Timing\Benchmarking;

	require_once(__DIR__ . '/BenchmarkResult.php');

	/**
	 * Interface IBenchmark
	 * @package Kramework\Timing\Benchmarking
	 */
	interface IBenchmark
	{
		/**
		 * Initiate the benchmark test.
		 * @return BenchmarkResult
		 */
		public function runTest():BenchmarkResult;

		/**
		 * Overwrite and include the code to benchmark inside this function.
		 */
		public function execute();

		/**
		 * Called just before the test is run.
		 */
		public function onStart();

		/**
		 * Called just after the test is run.
		 */
		public function onEnd();
	}

	/**
	 * Class Benchmark
	 * @package Kramework\Timing\Benchmarking
	 */
	abstract class Benchmark implements IBenchmark
	{
		/**
		 * Benchmark constructor.
		 * @param int $cycles How many execution cycles?
		 * @param int $executionsPerCycle How many executions per cycle?
		 * @param string $name A name to identify this benchmark.
		 */
		public function __construct(int $cycles = 200, int $executionsPerCycle = 1000, string $name = null) {
			$this->name = $name;
			$this->sets = $cycles;
			$this->executions = $executionsPerCycle;
		}

		/**
		 * Called just before the test is run.
		 */
		public function onStart() {
			// Overwrite to obtain functionality.
		}

		/**
		 * Called just after the test is run.
		 */
		public function onEnd() {
			// Overwrite to obtain functionality.
		}

		/**
		 * Initiate the test.
		 * @return BenchmarkResult
		 */
		public function runTest():BenchmarkResult {
			$this->onStart();

			$testStartTime = microtime(true); // Test start time.
			$cycleTimes = array_fill(0, $this->sets, 0); // Pre-allocate.

			// Execute the sets.
			for ($i = 0; $i < $this->sets; $i++) {
				$cycleStartTime = microtime(true);

				for ($e = 0; $e < $this->executions; $e++)
					$this->execute();

				$cycleTimes[$i] = microtime(true) - $cycleStartTime;
			}

			$testEndTime = microtime(true) - $testStartTime; // Grab the test end time.

			// Process short/long times.
			$shortTime = null;
			$longTime = null;

			foreach ($cycleTimes as $entry) {
				$shortTime = $shortTime == null ? $entry : min($entry, $shortTime);
				$longTime = $longTime == null ? $entry : max($entry, $longTime);
			}

			$result = new BenchmarkResult(
				array_sum($cycleTimes) / count($cycleTimes), // Average time
				$shortTime, // Shortest cycle time
				$longTime, // Longest cycle time
				$testEndTime, // Elapsed time
				$this->sets, // Set count
				$this->executions, // Executions per set.
				$this->name // Benchmark name
			);

			$this->onEnd();
			return $result;
		}

		/**
		 * @var int
		 */
		private $sets;

		/**
		 * @var int
		 */
		private $executions;

		/**
		 * @var string
		 */
		private $name;
	}