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

	class HTMLReportFormatter implements IBenchmarkReportFormatter
	{
		/**
		 * Format the given results.
		 *
		 * @api
		 * @param BenchmarkResult[] $results
		 * @return string
		 */
		public function format(array $results): string {
			/*
			 * <table>
			 * 		<tr>
			 * 			<td></td>
			 * 		</tr>
			 * </table>
			 */
			$builder = new StringBuilder();
			$builder->append('<table><tr>');
			$builder->append('<th>Benchmark</th>', '<th>Average</th>', '<th>Elapsed</th>');
			$builder->append('<th>Shortest</th>', '<th>Longest</th>', '<th>StdDev</th>');
			$builder->append('<th>Sets</th>', '<th>Execs (p/set)</th>');
			$builder->append('</tr>');

			foreach ($results as $result) {
				$builder->append('<tr>');
				$builder->append('<td>', $result->getName(), '</td>');
				$builder->append('<td>', $result->getAverageFormatted(), '</td>');
				$builder->append('<td>', $result->getElapsedFormatted(), '</td>');
				$builder->append('<td>', $result->getShortestFormatted(), '</td>');
				$builder->append('<td>', $result->getLongestFormatted(), '</td>');
				$builder->append('<td>', $result->getStandardDeviationFormatted(), '</td>');
				$builder->append('<td>', $result->getSetCount(), '</td>');
				$builder->append('<td>', $result->getExecutionsPerSet(), '</td>');
				$builder->append('</tr>');
			}

			$builder->append('</table');
			return $builder;
		}
	}