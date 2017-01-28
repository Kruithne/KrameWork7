<?php
	namespace Kramework\Utils\Timing;

	interface IBenchmark
	{
		/**
		 * Initiate the benchmark test.
		 * @return \ArrayObject
		 */
		public function runTest():\ArrayObject;

		/**
		 * Overwrite and include the code to benchmark inside this function.
		 */
		public function runCycle();

		/**
		 * Called just before the test is run.
		 */
		public function onStart();

		/**
		 * Called just after the test is run.
		 */
		public function onEnd();
	}

	abstract class Benchmark implements IBenchmark
	{
		/**
		 * Benchmark constructor.
		 * @param int $cycles How many times should runCycle() be called?
		 */
		public function __construct(int $cycles = 2000) {
			$this->cycles = $cycles;
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
		 * @return \ArrayObject
		 */
		public function runTest():\ArrayObject {
			$this->onStart();

			$start = microtime(true);
			$shortTime = null;
			$longTime = null;
			$cycleTimes = [];

			for ($i = 0; $i < $this->cycles; $i++) {
				$cycleStartTime = microtime(true);

				$this->runCycle();

				$cycleTime = microtime(true) - $cycleStartTime;
				if ($shortTime == null || $cycleTime < $shortTime)
					$shortTime = $cycleTime;

				if ($longTime == null || $cycleTime > $longTime)
					$longTime = $cycleTime;

				$cycleTimes[] = $cycleTime;
			}

			$this->onEnd();

			return new \ArrayObject([
				"executionTime" => microtime(true) - $start,
				"averageCycleTime" => array_sum($cycleTimes) / count($cycleTimes),
				"shortestCycle" => $shortTime,
				"longestCycle" => $longTime,
			], \ArrayObject::ARRAY_AS_PROPS);
		}

		private $cycles;
	}