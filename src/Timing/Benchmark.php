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

	namespace Kramework\Timing;

	/**
	 * Interface IBenchmark
	 * @package Kramework\Utils\Timing
	 */
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

	/**
	 * Class Benchmark
	 * @package Kramework\Utils\Timing
	 */
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

			$result = new \ArrayObject([
				"executionTime" => microtime(true) - $start,
				"averageCycleTime" => array_sum($cycleTimes) / count($cycleTimes),
				"shortestCycleTime" => $shortTime,
				"longestCycleTime" => $longTime,
			], \ArrayObject::ARRAY_AS_PROPS);

			$this->onEnd();
			return $result;
		}

		private $cycles;
	}