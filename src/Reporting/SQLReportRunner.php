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

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 * @author docpify <morten@runsafe.no>
	 */
	abstract class SQLReport extends ReportRunner
	{
		/**
		 * SQLReport constructor.
		 * @param IDataCache $cache
		 * @param object $db Database Access Layer, needs a query() method FIXME Missing API in KW7
		 * @param string $sql An SQL Query
		 * @param array $param Query parameters
		 * @param bool $debug Enable or disable debugging information from the DBMS
		 * @param int $cacheTTL Number of seconds to store the results in cache.
		 */
		public function __construct(IDataCache $cache, object $db, string $sql, array $param = [], bool $debug = false, int $cacheTTL = 300) {
			$this->db = $db;
			$this->sql = $sql;
			$this->param = $param;
			$this->debug = $debug;
			parent::__construct($cache, sha1(serialize([$db, $sql, $param])), $cacheTTL);
		}

		/**
		 * Executes the report, storing the results in APC
		 */
		protected function run() {
			// FIXME We need a database component in KW7 before this can be actually implemented.
			return $this->postProcess($this->db->query($this->sql, $this->param, DB_RESULT_SET, $this->debug));
		}

		/**
		 * @var mixed Database engine
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

		/**
		 * @var bool Debugging enabled
		 */
		private $debug;
	}
