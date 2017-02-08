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

	use KrameWork\Utils\StringBuilder;

	require_once(__DIR__ . '/IBenchmarkReportFormatter.php');
	require_once(__DIR__ . '/../../Utils/StringBuilder.php');

	/**
	 * Class MarkdownReportFormatter
	 * Markdown formatter for benchmark reports.
	 *
	 * @package KrameWork\Timing\Benchmarking
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class MarkdownReportFormatter implements IBenchmarkReportFormatter
	{
		/**
		 * Format the given results.
		 *
		 * @api
		 * @param BenchmarkResult[] $results
		 * @return string
		 */
		public function format(array $results): string {
			$builder = new StringBuilder();
			$builder->setSeparator(' | ');
			$builder->append('Benchmark', 'AverageTime', 'ExecutionTime');
			$builder->append('ShortestCycle', 'LongestCycle', 'CycleCount');
			$builder->newLine()->repeat('---', 6);

			foreach ($results as $result) {
				$builder->newLine();
				$builder->append($result->getName(), $result->getAverageFormatted(), $result->getElapsedFormatted());
				$builder->append($result->getShortestFormatted(), $result->getLongestFormatted(), $result->getCount());
			}

			return $builder;
		}
	}