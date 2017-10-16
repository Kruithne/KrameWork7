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

	use KrameWork\Caching\IDataCache;
	use KrameWork\Database\Driver\Generic;

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Reporting
	 */
	abstract class SQLReport extends ReportRunner
	{
		/**
		 * SQLReport constructor.
		 * @api __construct
		 * @param IDataCache $cache A cache to hold report data
		 * @param Generic $db A database access object ie. Database
		 * @param string $sql An SQL Query
		 * @param array $param Query parameters
		 * @param int $cacheTTL Number of seconds to store the results in cache.
		 */
		public function __construct(IDataCache $cache, Generic $db, string $sql, array $param = [], int $cacheTTL = 300) {
			$this->db = $db;
			$this->sql = $sql;
			$this->param = $param;
			parent::__construct($cache, \sha1(\serialize([$db, $sql, $param])), $cacheTTL);
		}

		/**
		 * Executes the report, storing the results in APC
		 * @api run
		 */
		protected function run() {
			return $this->db->getAll($this->sql, $this->param);
		}

		/**
		 * Format data values for presentation based on column type
		 * @param \stdClass[] $data
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
		 * @var Generic Database engine
		 */
		private $db;

		/**
		 * @var string SQL Query
		 */
		private $sql;

		/**
		 * @var array SQL Parameters
		 */
		private $param;
	}
