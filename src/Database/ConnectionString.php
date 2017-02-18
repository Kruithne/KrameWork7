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
	 * Base class for a database connection string
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	class ConnectionString
	{
		/**
		 * ConnectionString constructor.
		 * @api __construct
		 * @param $connection string A connection string
		 * @param string $username A username to log on
		 * @param string $password A password to log on
		 */
		public function __construct(string $connection, string $username = null, string $password = null) {
			$this->$connection = $connection;
			$this->username = $username;
			$this->password = $password;
		}

		/**
		 * @api __toString
		 * @return string The connection string
		 */
		function __toString() {
			return $this->connection;
		}

		/**
		 * @return string
		 */
		public function getUsername(): string {
			return $this->username;
		}

		/**
		 * @return string
		 */
		public function getPassword(): string {
			return $this->password;
		}

		/**
		 * @var string The connection string
		 */
		protected $connection;
		protected $username;
		protected $password;
	}