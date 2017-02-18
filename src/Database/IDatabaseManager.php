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
	 * Base class for a database connection driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	interface IDatabaseManager
	{
		/**
		 * Add a managed table to the database
		 * @api __set
		 * @param string $name
		 * @param IManagedTable $table
		 */
		public function __set(string $name, IManagedTable $table);

		/**
		 * @api __get
		 * @param string $name A table name
		 * @return IDatabaseTable|IManagedTable A table driver
		 */
		public function __get(string $name);

		/**
		 * @api updateSchema
		 * Update the database schema according to managed tables
		 * @return array Managed table names as keys with status for each as values
		 */
		public function updateSchema(): array;
	}