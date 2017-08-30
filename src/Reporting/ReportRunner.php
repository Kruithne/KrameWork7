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

	require_once(__DIR__.'/ReportResults.php');
	require_once(__DIR__.'/ReportColumn.php');
	require_once(__DIR__.'/../Data/CurrencyValue.php');
	require_once(__DIR__.'/../Data/IntegerValue.php');
	require_once(__DIR__.'/../Data/DateTimeValue.php');
	require_once(__DIR__.'/../Data/DateValue.php');
	require_once(__DIR__.'/../Data/DecimalValue.php');
	require_once(__DIR__.'/../Data/StringValue.php');

	use KrameWork\Caching\IDataCache;
	use KrameWork\Data\Value;

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 * @author docpify <morten@runsafe.no>
	 */
	abstract class ReportRunner
	{
		/**
		 * ReportRunner constructor.
		 * @param IDataCache $cache The cache location for the report data
		 * @param string $key The key to access the cached report data
		 * @param int $cacheTTL The number of seconds the report should remain cached
		 */
		public function __construct(IDataCache $cache, string $key, int $cacheTTL = 300) {
			$this->cacheTTL = $cacheTTL;
			$this->key = $key;
			$this->cache = $cache;
		}

		/**
		 * Executes the report, return the data to be processed and stored in cache.
		 * Override this to implement your report.
		 * @return array
		 */
		protected abstract function run();

		/**
		 * Executes the report if necessary and returns the cached result set.
		 * @return ReportResults The data set returned by the SQL
		 */
		public function data(): ReportResults {
			if ($this->cache->exists($this->key))
				$data = $this->cache->__get($this->key);
			else
				$data = null;

			if($data == null)
			{
				$data = new ReportResults($this->postProcess($this->run()));
				$this->cache->store($this->key, $data, $this->cacheTTL);
			}
			return $data;
		}

		/**
		 * Flushes cached data, forcing a fresh run of the report
		 */
		public function clear() {
			$this->cache->__unset($this->key);
		}

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
						$filters[] = $this->makeFilter($key, 'KrameWork\\Data\\'. $col->type . 'Value');
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
				if (!isset($row->$key) || $row->$key == null || $row->$key instanceof Value)
					return;
				$row->$key = new $class($row->$key);
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

		/**
		 * @var int Cache Time To Live
		 */
		protected $cacheTTL;

		/**
		 * @var string Cache storage key
		 */
		protected $key;

		/**
		 * @var IDataCache Cache engine
		 */
		protected $cache;
	}
