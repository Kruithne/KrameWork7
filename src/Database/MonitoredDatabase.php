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
		 * @param ConnectionString $connection
		 * @param int $driver
		 * @param int $threshold Trigger a warning if execution takes longer than $threshold seconds
		 */
		function __construct(ConnectionString $connection, int $driver, int $threshold = 2) {
			parent::__construct($connection, $driver);
			$this->timer = new Timer(Timer::FORMAT_MICROSECONDS);
			$this->statistics = [];
			$this->threshold = $threshold;
		}

		function getAll(string $sql, array $param): array {
			$this->timer->start();
			$result = parent::getAll($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		function getColumn(string $sql, array $param): array {
			$this->timer->start();
			$result = parent::getColumn($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		function getRow(string $sql, array $param): \ArrayObject {
			$this->timer->start();
			$result = parent::getRow($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		function getValue(string $sql, array $param) {
			$this->timer->start();
			$result = parent::getValue($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		function execute(string $sql, array $param): int {
			$this->timer->start();
			$result = parent::execute($sql, $param);
			$this->log($sql, $param);
			return $result;
		}

		private function log($sql, $param) {
			$time = $this->timer->stop();
			if ($time > $this->threshold)
				trigger_error($this->formatWarning($sql, $param, $time), E_USER_WARNING);
			$key = json_encode([$sql, $param]);
			if (!isset($this->statistics[$key]))
				$this->statistics[$key] = [];
			$this->statistics[$key][] = $time;
		}

		private function formatWarning(string $sql, array $param, float $time) {
			return sprintf('Query completed in %3$.2e seconds: %$1$s {%2$s}', $sql, json_encode($param), $time);
		}

		/**
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