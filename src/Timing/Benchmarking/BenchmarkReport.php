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
	use KrameWork\Timing\Benchmarking\BenchmarkResult;
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
		 * @api __construct
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
		 * @api add
		 * @param Benchmark[] ...$benchmarks Benchmark to add to the report.
		 * @return BenchmarkReport
		 */
		public function add(Benchmark ...$benchmarks):BenchmarkReport {
			foreach ($benchmarks as $benchmark)
				$this->benchmarks[] = $benchmark;

			return $this;
		}

		/**
		 * Remove a benchmark from the report.
		 *
		 * @api remove
		 * @param Benchmark $benchmark Benchmark to remove from the report.
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
		 * @api clear
		 * @return BenchmarkReport
		 */
		public function clear():BenchmarkReport {
			$this->benchmarks = [];
			return $this;
		}

		/**
		 * Run all tests contained in the report and return a formatted output.
		 *
		 * @api run
		 * @return string
		 */
		public function run():string {
			$results = [];
			foreach ($this->benchmarks as $benchmark)
				$results[] = $benchmark->runTest();

			usort($results, [$this, 'sortResults']);

			return $this->formatter->format($results);
		}

		/**
		 * Comparator for result sorting.
		 *
		 * @internal
		 * @param BenchmarkResult $a
		 * @param BenchmarkResult $b
		 * @return int
		 */
		public function sortResults(BenchmarkResult $a, BenchmarkResult $b) {
			if ($a->getAverage() == $b->getAverage()) {
				return 0;
			}
			return ($a->getAverage() < $b->getAverage()) ? -1 : 1;
		}

		/**
		 * Runs all of the tests and returns the output.
		 *
		 * @api __toString
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