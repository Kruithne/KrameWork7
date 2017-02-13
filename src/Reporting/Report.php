<?php
	/*
	 * Copyright (c) 2017 Morten Nilsen (morten@runsafe.no)
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

	namespace KrameWork\Reporting;

	/**
	 * Class SQLReport
	 * Encapsulates a formatted SQL report, adding column definitions with data types
	 */
	abstract class Report extends ReportRunner
	{
		/**
		 * @return ReportColumn[]
		 */
		public abstract function columns();

		/**
		 * @return \Closure[] Filters in the format function(&$row)
		 */
		protected function getFilters() {
			$filters = [];
			foreach ($this->columns() as $key => $col) {
				switch ($col->type) {
					case ReportColumn::COL_DECIMAL:
					case ReportColumn::COL_CURRENCY:
					case ReportColumn::COL_INTEGER:
					case ReportColumn::COL_DATETIME:
					case ReportColumn::COL_DATE:
						$filters[] = self::makeFilter($key, $col->type . 'Value');
						break;
				}
			}
			return $filters;
		}

		/**
		 * @param string $key Column name
		 * @param string $class Column container class
		 * @return \Closure
		 */
		protected function makeFilter(string $key, string $class) {
			return function (&$row) use ($key, $class) {
				if (!isset($row[$key]) || $row[$key] == null)
					return;
				$row[$key] = new $class($row[$key]);
			};
		}

		/**
		 * Format data values for presentation based on column type
		 * @param array $data
		 * @return ReportRow[]
		 */
		protected function postProcess(array $data) {
			$filters = $this->getFilters();
			$output = [];
			foreach ($data as $row) {
				foreach ($filters as $filter)
					$filter($row);
				$output[] = new ReportRow($row);
			}
			return $output;
		}
	}