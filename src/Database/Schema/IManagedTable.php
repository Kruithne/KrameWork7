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

	namespace KrameWork\Database\Schema;

	/**
	 * Base class for a database table driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	interface IManagedTable
	{
		/**
		 * @api getSchema
		 * Returns the name of the database schema
		 * @return string The database schema name
		 */
		public function getSchema();

		/**
		 * @api getName
		 * Returns the name of the database table
		 * @return string The database table name
		 */
		public function getName();

		/**
		 * @api getFullName
		 * Returns the fully qualified table name, including schema.
		 * @return string Fully qualified table name
		 */
		public function getFullName();

		/**
		 * @api getRevision
		 * Returns a set of revision definitions
		 * @return TableVersion[]
		 */
		public function versionLog();

		/**
		 * @api getLatestVersion
		 * Returns a set of queries to create the latest revision of the table
		 * @return string[]
		 */
		public function latestVersion();

		/**
		 * @api update
		 * Updates the table in the database
		 * @return string Status
		 */
		public function update();

		/**
		 * @api drop
		 * Delete the table from the database
		 * @return bool Success
		 */
		public function drop();

		/**
		 * @api create
		 * Create the table in the database
		 * @return bool Success
		 */
		public function create();
	}
