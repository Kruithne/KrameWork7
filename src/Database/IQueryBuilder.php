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

	// These interfaces only provide hinting for IDEs when using the query builder features of CRUD.
	// They should not be used in production.

	interface IQueryAnd
	{
		/**
		 * Adds an AND directive to the SQL statement
		 * @param string $column The column name to filter by
		 * @return IQueryColumn
		 */
		public function andColumn($column);
	}

	interface IQueryBetween
	{
		/**
		 * Do a range match on the preceeding column specification
		 * @param mixed $low The lower bound.
		 * @param mixed $high The upper bound.
		 * @return IQueryPredicate
		 */
		public function between($low, $high);
	}

	interface IQueryEquals
	{
		/**
		 * Do an exact match on the preceding column specification
		 * @param string $value The pattern to look for or exclude.
		 * @return IQueryPredicate
		 */
		public function equals($value);
	}

	interface IQueryEqualsCaseInsensitive
	{
		/**
		 * Do a case-insensitive match on the preceding column specification
		 * @param string $value The pattern to look for or exclude.
		 * @return IQueryPredicate
		 */
		public function equalsCaseInsensitive($value);
	}

	interface IQueryGreaterThan
	{
		/**
		 * Check for values above the specified value.
		 * @param string $value The pattern to look for or exclude.
		 * @return IQueryPredicate
		 */
		public function greaterThan($value);
	}

	interface IQueryLessThan
	{
		/**
		 * Check for values below the specified value.
		 * @param string $value The pattern to look for or exclude.
		 * @return IQueryPredicate
		 */
		public function lessThan($value);
	}

	interface IQueryLike
	{
		/**
		 * Do a wildcard match on the preceding column specification
		 * @param string $value The pattern to look for or exclude.
		 * @return IQueryPredicate
		 */
		public function like($value);
	}

	interface IQueryNotLike
	{
		/**
		 * Do a negative wildcard match on the preceding column specification
		 * @param string $value The pattern to look for or exclude.
		 * @return IQueryPredicate
		 */
		public function notLike($value);
	}

	interface IQueryNull
	{
		/**
		 * Require that the preceding column specification is null
		 * @return IQueryPredicate
		 */
		public function isNull();
	}

	interface IQueryNotNull
	{
		/**
		 * Require that the preceding column specification is a non null value
		 * @return IQueryPredicate
		 */
		public function notNull();
	}

	interface IQueryMaximum
	{
		/**
		 * Select rows where the value of the preceding column specification is at the maximum
		 * @return IQueryPredicate
		 */
		public function maximum();
	}

	interface IQueryMinimum
	{
		/**
		 * Select rows where the value of the preceding column specification is at the minimum
		 * @return IQueryPredicate
		 */
		public function minimum();
	}

	interface IQueryOr
	{
		/**
		 * Adds an OR directive to the SQL statement
		 * @param string $column The column name to filter by
		 * @return IQueryColumn
		 */
		public function orColumn($column);
	}

	interface IQueryTerminus
	{
		/**
		 * Prepare and execute the built query, returning the result set.
		 * @return object[] The data-type as specified by CRUD instance the query was built from.
		 */
		public function execute();
	}

	interface IQueryOrderBy extends IQueryTerminus
	{
		/**
		 * Order the result set by a column in descending order
		 * @param string $column A column name to order by in descending order
		 * @return IQueryOrderBy
		 */
		public function descending($column);

		/**
		 * Order the result set by a column in ascending order
		 * @param string $column A column name to order by in ascending order
		 * @return IQueryOrderBy
		 */
		public function ascending($column);
	}

	interface IQueryLimit extends IQueryTerminus
	{
		/**
		 * Only return N rows from the dataset
		 * @param int $count The maximum number of rows to return.
		 * @return IQueryTerminus
		 */
		public function limit($count);
	}

	interface IQueryOffset extends IQueryTerminus
	{
		/**
		 * Skip the first N rows in the dataset
		 * @param int $offset The number of rows to skip
		 * @return IQueryOffset
		 */
		public function offset($offset);
	}

	interface IQueryColumn extends IQueryBetween, IQueryEquals, IQueryEqualsCaseInsensitive, IQueryGreaterThan, IQueryLessThan, IQueryLike, IQueryNotLike, IQueryNull, IQueryNotNull, IQueryMaximum, IQueryMinimum
	{
	}

	interface IQueryPredicate extends IQueryAnd, IQueryOr, IQueryLimit, IQueryOffset, IQueryOrderBy
	{
	}
