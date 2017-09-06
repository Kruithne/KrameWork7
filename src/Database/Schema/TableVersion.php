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

	use \KrameWork\Database\Driver\Generic;

	class TableVersion
	{
		public function __construct(int $version, array $sql) {
			$this->version = $version;
			$this->sql = $sql;
		}

		/**
		 * @api BeforeExecution
		 * Override this function to execute custom code before SQL application.
		 */
		public function BeforeExecution(Generic $db) {
		}

		/**
		 * @api BeforeExecution
		 * Override this function to execute custom code after SQL application.
		 */
		public function AfterExecution(Generic $db) {
		}

		/**
		 * @var int
		 */
		public $version;

		/**
		 * @var string[]
		 */
		public $sql;
	}
