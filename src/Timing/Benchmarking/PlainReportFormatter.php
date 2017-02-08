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
	 * Class PlainReportFormatter
	 * Plain-text formatter for benchmark reports.
	 *
	 * @package KrameWork\Timing\Benchmarking
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class PlainReportFormatter implements IBenchmarkReportFormatter
	{
		/**
		 * Format the given results.
		 *
		 * @api
		 * @param \ArrayObject[] $results
		 * @return string
		 */
		public function format(array $results): string {
			$builder = new StringBuilder();

			$this->addField($builder, 'ExecutionTime');
			$this->addField($builder, 'AverageTime');
			$this->addField($builder, 'ShortestCycle');
			$this->addField($builder, 'LongestCycle');
			$builder->newLine();

			foreach ($results as $result) {
				$this->addField($builder, $result->executionTime);
				$this->addField($builder, $result->averageCycleTime);
				$this->addField($builder, $result->shortestCycleTime);
				$this->addField($builder, $result->longestCycleTime);
				$builder->newLine();
			}

			return $builder;
		}

		/**
		 * Add a formatted field to the target string builder.
		 *
		 * @internal
		 * @param StringBuilder $target
		 * @param string $content
		 */
		private function addField(StringBuilder $target, string $content)
		{
			$target->append(str_pad($content, 18), "\t");
		}
	}