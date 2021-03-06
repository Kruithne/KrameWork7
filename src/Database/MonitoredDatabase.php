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

	namespace KrameWork\Database;

	use KrameWork\Timing\Timer;

	require_once(__DIR__ . '/Database.php');
	require_once(__DIR__ . '/../Timing/Timer.php');

	/**
	 * Database connection with build in performance monitoring
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	class MonitoredDatabase extends Database
	{
		/**
		 * MonitoredDatabase constructor.
		 * @api __construct
		 * @param ConnectionString $connection A connection string
		 * @param int $driver A Database::DB_DRIVER_ constant
		 * @param int $threshold Trigger a warning if execution takes longer than $threshold seconds
		 */
		function __construct(ConnectionString $connection, int $driver, int $threshold = 2) {
			parent::__construct($connection, $driver);
			$this->timer = new Timer(Timer::FORMAT_MICROSECONDS);
			$this->statistics = [];
			$this->threshold = $threshold;
		}

		/**
		 * Execute a query and return an array of ArrayObjects
		 * @api getAll
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \ArrayObject[]
		 */
		function getAll(string $sql, array $param): array {
			$this->timer->start();
			$result = parent::getAll($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		/**
		 * Execute a query and return the first column of each row
		 * @api getColumn
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return array
		 */
		function getColumn(string $sql, array $param): array {
			$this->timer->start();
			$result = parent::getColumn($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		/**
		 * Execute a query and return the first row as an ArrayObject
		 * @api getRow
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \ArrayObject|null
		 */
		function getRow(string $sql, array $param) {
			$this->timer->start();
			$result = parent::getRow($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		/**
		 * Execute a query and return the first value of the first row
		 * @api getValue
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return mixed
		 */
		function getValue(string $sql, array $param) {
			$this->timer->start();
			$result = parent::getValue($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		/**
		 * Execute a statement and return the number of affected rows
		 * @api execute
		 * @param string $sql An SQL statement
		 * @param array $param An array of values to inject in the statement
		 * @return int
		 */
		function execute(string $sql, array $param): int {
			$this->timer->start();
			$result = parent::execute($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		/**
		 * Log timing information about a completed database statement.
		 * @internal
		 * @param $sql
		 * @param $param
		 */
		private function log($sql, $param) {
			$time = $this->timer->stop();
			$start = (string)$this->timer->getStartTimestamp();
			if ($time > $this->threshold)
				\trigger_error($this->formatWarning($sql, $param, $time), E_USER_WARNING);

			// It is highly unlikely two queries would have the same timestamp;
			// But just to be safe, we pack this into an array.
			if (!isset($this->statistics[$start]))
				$this->statistics[$start] = [];
			$this->statistics[$start][] = [$sql, $param, $time];
		}

		/**
		 * Format a warning message when a statement takes longer to execute than the configured threshold.
		 * @internal
		 * @param string $sql The SQL statement that was executed.
		 * @param array $param The parameters it was executed with.
		 * @param float $time The number of seconds execution took.
		 * @return string
		 */
		private function formatWarning(string $sql, array $param, float $time) {
			return \sprintf('Query completed in %3$.2e seconds: %1$s {%2$s}', $sql, \json_encode($param), $time);
		}

		/**
		 * Returns statistics data collected over the lifetime of the object
		 * @api getStatistics
		 * @return array
		 */
		public function getStatistics(): array {
			return $this->statistics;
		}

		/**
		 * @var Timer
		 */
		private $timer;

		/**
		 * @var array
		 */
		private $statistics;

		/**
		 * @var int
		 */
		private $threshold;
	}
