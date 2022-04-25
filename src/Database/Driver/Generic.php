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

	namespace KrameWork\Database\Driver;

	/**
	 * This interface defined the methods a database driver need to support the KrameWork database stack.
	 * @author docpify <morten@runsafe.no>
	 * @author Kruithne <kruithne@gmail.com>
	 * @package KrameWork\Database\Driver
	 */
	interface Generic
	{
		/**
		 * Execute a query and return an array of ArrayObjects
		 * @api getAll
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \stdClass[]
		 */
		function getAll(string $sql, array $param): array;

		/**
		 * Execute a query and return the first row as an ArrayObject
		 * @api getRow
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return \stdClass|null
		 */
		function getRow(string $sql, array $param);

		/**
		 * Execute a query and return the first column of each row
		 * @api getColumn
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return array
		 */
		function getColumn(string $sql, array $param): array;

		/**
		 * Execute a query and return the first value of the first row
		 * @api getValue
		 * @param string $sql An SQL query statement
		 * @param array $param An array of values to inject in the statement
		 * @return mixed|null
		 */
		function getValue(string $sql, array $param);

		/**
		 * Execute a statement and return the number of affected rows
		 * @api execute
		 * @param string $sql An SQL statement
		 * @param array $param An array of values to inject in the statement
		 * @return int
		 */
		function execute(string $sql, array $param): int;

		/**
		 * Return the last error for this database connection
		 * For query-specific errors, use getLastQueryError().
		 * @return mixed
		 */
		function getLastError();

		/**
		 * Returns the error for the last executed query.
		 * @return mixed
		 */
		function getLastQueryError();

		/**
		 * Returns the ID of the last inserted row.
		 * @return string
		 */
		function getLastInsertID(): string;

		/**
		 * @api beginTransaction
		 * Open a database transaction
		 */
		function beginTransaction();

		/**
		 * @api rollbackTransaction
		 * Rolls back the current transaction
		 */
		function rollbackTransaction();

		/**
		 * @api commitTransaction
		 * Commits the current transaction
		 */
		function commitTransaction();
	}