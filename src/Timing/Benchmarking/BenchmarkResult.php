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

	namespace KrameWork\Timing\Benchmarking;

	/**
	 * Class BenchmarkResult
	 * Contains results from a benchmark.
	 *
	 * @package KrameWork\Timing\Benchmarking
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class BenchmarkResult
	{
		/**
		 * BenchmarkResult constructor.
		 *
		 * @api
		 * @param float $average Average cycle time.
		 * @param float $shortest Shortest cycle time.
		 * @param float $longest Longest cycle time.
		 * @param float $elapsed Elapsed benchmark time.
		 * @param int $sets Amount of sets to execute.
		 * @param int $execs Executions per set.
		 * @param string|null $name Name of the benchmark.
		 */
		public function __construct(float $average, float $shortest, float $longest, float $elapsed, int $sets, int $execs, $name = null) {
			$this->average = $average;
			$this->shortest = $shortest;
			$this->longest = $longest;
			$this->elapsed = $elapsed;
			$this->sets = $sets;
			$this->executions = $execs;
			$this->name = $name ?? 'Benchmark' . self::$index++;

			$this->format = '%.5e';
		}

		/**
		 * Set the format used for formatted methods.
		 *
		 * @api
		 * @param $format String format.
		 */
		public function setFormat($format) {
			$this->format = $format;
		}

		/**
		 * Get the benchmark name.
		 *
		 * @api
		 * @return string
		 */
		public function getName():string {
			return $this->name;
		}

		/**
		 * Get the average cycle time.
		 *
		 * @api
		 * @return float
		 */
		public function getAverage():float {
			return $this->average;
		}

		/**
		 * Get the average cycle time (formatted).
		 *
		 * @api
		 * @return string
		 */
		public function getAverageFormatted():string {
			return sprintf($this->format, $this->average);
		}

		/**
		 * Get the shortest cycle time.
		 *
		 * @api
		 * @return float
		 */
		public function getShortest():float {
			return $this->shortest;
		}

		/**
		 * Get the shortest cycle time (formatted).
		 *
		 * @api
		 * @return string
		 */
		public function getShortestFormatted():string {
			return sprintf($this->format, $this->shortest);
		}

		/**
		 * Get the longest cycle time.
		 *
		 * @api
		 * @return float
		 */
		public function getLongest():float {
			return $this->longest;
		}

		/**
		 * Get the longest cycle time (formatted).
		 *
		 * @api
		 * @return string
		 */
		public function getLongestFormatted():string {
			return sprintf($this->format, $this->longest);
		}

		/**
		 * Get the total elapsed time.
		 *
		 * @api
		 * @return float
		 */
		public function getElapsed():float {
			return $this->elapsed;
		}

		/**
		 * Get the total elapsed time (formatted).
		 *
		 * @api
		 * @return string
		 */
		public function getElapsedFormatted():string {
			return sprintf($this->format, $this->elapsed);
		}

		/**
		 * Get the set count.
		 *
		 * @api
		 * @return int
		 */
		public function getSetCount():int {
			return $this->sets;
		}

		/**
		 * Get how many executions occur per set.
		 *
		 * @api
		 * @return int
		 */
		public function getExecutionsPerSet():int {
			return $this->executions;
		}

		/**
		 * @var string
		 */
		private $name;

		/**
		 * @var float
		 */
		private $average;

		/**
		 * @var float
		 */
		private $shortest;

		/**
		 * @var float
		 */
		private $longest;

		/**
		 * @var float
		 */
		private $elapsed;

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
		private $format;

		/**
		 * @var int
		 */
		private static $index = 1;
	}