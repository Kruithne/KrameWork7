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

	use KrameWork\Caching\IDataCache;

	/**
	 * Wrapper class to automatically cache query results
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	class DatabaseCache
	{
		public function __construct(IDataCache $cache, Database $database) {
			$this->cache = $cache;
			$this->database = $database;
		}

		/**
		 * Execute a query and return an array of ArrayObjects
		 * @api getAll
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @param int $ttl Number of seconds to cache the results, 0 forces refresh
		 * @return array|\ArrayObject[]
		 */
		function getAll(string $sql, array $param, int $ttl): array {
			$key = 'all_' . $this->getKey($sql, $param);
			if ($this->cache->exists($key) && $ttl > 0)
				return $this->cache->__get($key);

			$data = $this->database->getAll($sql, $param);
			$this->cache->store($key, $data, $ttl);
			return $data;
		}

		/**
		 * Execute a query and return the first row as an ArrayObject
		 * @api getRow
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @param int $ttl Number of seconds to cache the results, 0 forces refresh
		 * @return \ArrayObject
		 */
		function getRow(string $sql, array $param, int $ttl): \ArrayObject {
			$key = 'row_' . $this->getKey($sql, $param);
			if ($this->cache->exists($key) && $ttl > 0)
				return $this->cache->__get($key);

			$data = $this->database->getRow($sql, $param);
			$this->cache->store($key, $data, $ttl);
			return $data;
		}

		/**
		 * Execute a query and return the first column of each row
		 * @api getColumn
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @param int $ttl Number of seconds to cache the results, 0 forces refresh
		 * @return array
		 */
		function getColumn(string $sql, array $param, int $ttl): array {
			$key = 'col_' . $this->getKey($sql, $param);
			if ($this->cache->exists($key) && $ttl > 0)
				return $this->cache->__get($key);

			$data = $this->database->getColumn($sql, $param);
			$this->cache->store($key, $data, $ttl);
			return $data;
		}

		/**
		 * Execute a query and return the first value of the first row
		 * @api getValue
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @param int $ttl Number of seconds to cache the results, 0 forces refresh
		 * @return mixed
		 */
		function getValue(string $sql, array $param, int $ttl) {
			$key = 'val_' . $this->getKey($sql, $param);
			if ($this->cache->exists($key) && $ttl > 0)
				return $this->cache->__get($key);

			$data = $this->database->getValue($sql, $param);
			$this->cache->store($key, $data, $ttl);
			return $data;
		}

		/**
		 * Execute a statement and return the number of affected rows
		 * This method does not cache queries.
		 * @api execute
		 * @param string $sql An SQL statement
		 * @param array $param An array of values to inject in the statement
		 * @return int
		 */
		function execute(string $sql, array $param): int {
			return $this->database->execute($sql, $param);
		}

		/**
		 * @param $sql string An SQL statement
		 * @param $param array SQL parameters
		 * @return string The cache key
		 */
		private function getKey($sql, $param): string {
			return sha1(json_encode([$sql, $param]));
		}

		/**
		 * @var IDataCache
		 */
		private $cache;

		/**
		 * @var Database
		 */
		private $database;
	}