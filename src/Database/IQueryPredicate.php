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

	/**
	 * Base class for a database table driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	interface IQueryPredicate
	{
		/**
		 * Apply an offset to the result set
		 * @api offsset
		 * @param int $offset
		 * @return IQueryPredicate
		 */
		public function offset(int $offset): IQueryPredicate;

		/**
		 * Limit the number of rows matched
		 * @api limit
		 * @param int $limit
		 * @return IQueryPredicate
		 */
		public function limit(int $limit): IQueryPredicate;

		/**
		 * Sort the result set by a column in ascending order
		 * @api orderBy
		 * @param string $column Column name
		 * @return IQueryPredicate
		 */
		public function orderBy(string $column): IQueryPredicate;

		/**
		 * Sort the result set by a column in descending order
		 * @api orderByDesc
		 * @param string $column Column name
		 * @return IQueryPredicate
		 */
		public function orderByDesc(string $column): IQueryPredicate;

		/**
		 * Execute a select statement and return the data set
		 * @api select
		 * @return array array of arrays of key/value pairs
		 */
		public function select(): array;

		/**
		 * @api update
		 * @param array $updateSpec Array of key/value pairs to persist to the database
		 * @return bool Success
		 */
		public function update(array $updateSpec): bool;

		/**
		 * Delete the matched rows
		 * @api delete
		 * @return bool Success
		 */
		public function delete(): bool;
	}