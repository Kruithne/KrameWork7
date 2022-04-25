<?php
	/*
	 * Copyright (c) 2017 Morten Nilsen (morten@runsafe.no)
	 * Copyright (c) 2021 Kruithne (kruithne@gmail.com)
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

	require_once(__DIR__ . '/Driver/Generic.php');
	require_once(__DIR__ . '/ConnectionString.php');

	class UnknownDriverException extends \Exception {}

	/**
	 * Base class for a database connection
	 * @author docpify <morten@runsafe.no>
	 * @author Kruithne <kruithne@gmail.com>
	 * @package KrameWork\Database
	 */
	class Database implements Driver\Generic
	{
		/**
		 * Connect using the PDO driver
		 */
		const DB_DRIVER_FAKE = 0; // For unit tests
		const DB_DRIVER_PDO = 1;

		/**
		 * Database constructor.
		 * @api __construct
		 * @param ConnectionString $connection Connection string specifying how to connect
		 * @param int $driver One of the Database::DB_DRIVER_ constants
		 * @throws UnknownDriverException
		 */
		public function __construct(ConnectionString $connection, int $driver = self::DB_DRIVER_PDO) {
			switch ($driver) {
				case self::DB_DRIVER_FAKE:
					$this->driver = new Driver\Fake($connection);
					break;
				case self::DB_DRIVER_PDO:
					require_once(__DIR__ . '/Driver/PDO.php');
					$this->driver = new Driver\PDO($connection);
					break;
				default:
					throw new UnknownDriverException('Unsupported driver type '.$driver);
			}
		}

		/**
		 * @var Driver\Generic The database driver we are using to run queries
		 */
		private $driver;

		/**
		 * Execute a query and return an array of ArrayObjects
		 * @api getAll
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \stdClass[]
		 */
		function getAll(string $sql, array $param): array {
			return $this->driver->getAll($sql, $param);
		}

		/**
		 * Execute a query and return the first row as an ArrayObject
		 * @api getRow
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \stdClass|null
		 */
		function getRow(string $sql, array $param) {
			return $this->driver->getRow($sql, $param);
		}

		/**
		 * Execute a query and return the first column of each row
		 * @api getColumn
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return array
		 */
		function getColumn(string $sql, array $param): array {
			return $this->driver->getColumn($sql, $param);
		}

		/**
		 * Execute a query and return the first value of the first row
		 * @api getValue
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return mixed|null
		 */
		function getValue(string $sql, array $param) {
			return $this->driver->getValue($sql, $param);
		}

		/**
		 * Execute a statement and return the number of affected rows
		 * @api execute
		 * @param string $sql An SQL statement
		 * @param array $param An array of values to inject in the statement
		 * @return int
		 */
		function execute(string $sql, array $param): int {
			return $this->driver->execute($sql, $param);
		}

		/**
		 * Return the last error for this database connection
		 * For query-specific errors, use getLastQueryError().
		 * @return mixed
		 */
		function getLastError() {
			return $this->driver->getLastError();
		}

		/**
		 * Returns the error for the last executed query.
		 * @return mixed
		 */
		function getLastQueryError() {
			return $this->driver->getLastQueryError();
		}

		/**
		 * Returns the ID of the last inserted row.
		 * @return string
		 */
		function getLastInsertID(): string {
			return $this->driver->getLastInsertID();
		}

		function beginTransaction() {
			return $this->driver->beginTransaction();
		}

		function commitTransaction() {
			return $this->driver->commitTransaction();
		}

		function rollbackTransaction() {
			return $this->driver->rollbackTransaction();
		}
	}