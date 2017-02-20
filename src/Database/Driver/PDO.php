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

	namespace KrameWork\Database\Driver;

	use KrameWork\Database\ConnectionString;

	/**
	 * This driver wraps a PDO connection, supporting a wide range of database engines.
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database\Driver
	 */
	class PDO implements Generic
	{
		/**
		 * PDO constructor.
		 * @api __construct
		 * @param ConnectionString $connection
		 */
		public function __construct(ConnectionString $connection) {
			$this->connection = new \PDO($connection->__toString(), $connection->getUsername(), $connection->getPassword());
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}

		/**
		 * @var \PDO
		 */
		private $connection;

		/**
		 * Execute a query and return an array of ArrayObjects
		 * @api getAll
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \ArrayObject[]
		 */
		function getAll(string $sql, array $param): array {
			$query = $this->connection->prepare($sql);
			$this->bind($query, $param);
			$query->execute();
			$result = [];
			while($row = $query->fetchObject('\ArrayObject'))
				$result[] = $row;
			return $result;
		}

		/**
		 * Execute a query and return the first row as an ArrayObject
		 * @api getRow
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \ArrayObject
		 */
		function getRow(string $sql, array $param): \ArrayObject {
			$query = $this->connection->prepare($sql);
			$this->bind($query, $param);
			$query->execute();
			return new \ArrayObject($query->fetchObject());
		}

		/**
		 * Execute a query and return the first column of each row
		 * @api getColumn
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return array
		 */
		function getColumn(string $sql, array $param): array {
			$query = $this->connection->prepare($sql);
			$this->bind($query, $param);
			$query->execute();
			$result[] = [];
			while($row = $query->fetchColumn())
				$result[] = $row;
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
			$query = $this->connection->prepare($sql);
			$this->bind($query, $param);
			$query->execute();
			return $query->fetchColumn();
		}

		/**
		 * Execute a statement and return the number of affected rows
		 * @api execute
		 * @param string $sql An SQL statement
		 * @param array $param An array of values to inject in the statement
		 * @return int
		 */
		function execute(string $sql, array $param): int {
			$query = $this->connection->prepare($sql);
			$this->bind($query, $param);
			$query->execute();
			return $query->rowCount();
		}

		/**
		 * Bind the parameters to the prepared statement, guessing at parameter types.
		 * @param \PDOStatement $query A prepared statement
		 * @param array $param Parameters to bind
		 * @throws \Exception If a mixed usage of numeric and string indices are detected
		 */
		private function bind(\PDOStatement $query, array $param)
		{
			if(!$param || count($param) == 0)
				return;
			$syntax = null;
			foreach($param as $k => $v) {
				$type = \PDO::PARAM_STR;
				if(is_int($v))
					$type = \PDO::PARAM_INT;
				if(is_bool($v))
					$type = \PDO::PARAM_BOOL;

				if(is_int($k)) {
					if($syntax !== null && $syntax != 1)
						throw new \Exception("Mixed parameter type in parameter list");
					$syntax = 1;
					$query->bindValue($k + 1, $v, $type);
				} else {
					if($syntax !== null && $syntax != 2)
						throw new \Exception("Mixed parameter type in parameter list");
					$syntax = 2;
					$query->bindValue(':' . $k, $v, $type);
				}
			}
		}
	}