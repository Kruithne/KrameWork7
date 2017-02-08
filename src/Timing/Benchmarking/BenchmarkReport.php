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

	namespace KrameWork\Timing;

	use KrameWork\Timing\Benchmarking\Benchmark;
	use KrameWork\Timing\Benchmarking\HTMLReportFormatter;
	use KrameWork\Timing\Benchmarking\IBenchmarkReportFormatter;
	use KrameWork\Timing\Benchmarking\MarkdownReportFormatter;
	use KrameWork\Timing\Benchmarking\PlainReportFormatter;

	require_once(__DIR__ . '/Benchmark.php');

	/**
	 * Class BenchmarkReport
	 * Reporting tool to combine results of benchmarks.
	 *
	 * @package KrameWork\Timing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class BenchmarkReport
	{
		const FORMAT_PLAIN = 0x1;
		const FORMAT_MARKDOWN = 0x2;
		const FORMAT_HTML = 0x4;

		/**
		 * BenchmarkReport constructor.
		 *
		 * @api
		 * @param int $format Formatter to use, defaults to FORMAT_HTML.
		 */
		public function __construct(int $format = self::FORMAT_HTML) {
			$this->benchmarks = [];

			switch ($format) {
				case self::FORMAT_MARKDOWN:
					require_once(__DIR__ . '/MarkdownReportFormatter.php');
					$this->formatter = new MarkdownReportFormatter();
					break;

				case self::FORMAT_HTML:
					require_once(__DIR__ . '/HTMLReportFormatter.php');
					$this->formatter = new HTMLReportFormatter();
					break;

				case self::FORMAT_PLAIN:
				default:
				require_once(__DIR__ . '/PlainReportFormatter.php');
					$this->formatter = new PlainReportFormatter();
					break;
			}
		}

		/**
		 * Add a benchmark to this report.
		 *
		 * @api
		 * @param Benchmark $benchmark
		 * @return BenchmarkReport
		 */
		public function add(Benchmark $benchmark):BenchmarkReport {
			$this->benchmarks[] = $benchmark;
			return $this;
		}

		/**
		 * Remove a benchmark from the report.
		 *
		 * @api
		 * @param Benchmark $benchmark
		 * @return BenchmarkReport
		 */
		public function remove(Benchmark $benchmark):BenchmarkReport {
			if (($index = array_search($benchmark, $this->benchmarks)) !== false)
				unset($this->benchmarks[$index]);

			return $this;
		}

		/**
		 * Clear all benchmarks from the report.
		 *
		 * @api
		 * @return BenchmarkReport
		 */
		public function clear():BenchmarkReport {
			$this->benchmarks = [];
			return $this;
		}

		/**
		 * Run all tests contained in the report and return a formatted output.
		 *
		 * @api
		 * @return string
		 */
		public function run():string {
			$results = [];
			foreach ($this->benchmarks as $benchmark)
				$results[] = $benchmark->runTest();

			return $this->formatter->format($results);
		}

		/**
		 * Runs all of the tests and returns the output.
		 *
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		public function __toString() {
			return $this->run();
		}

		/**
		 * @var Benchmark[]
		 */
		private $benchmarks;

		/**
		 * @var IBenchmarkReportFormatter
		 */
		private $formatter;
	}