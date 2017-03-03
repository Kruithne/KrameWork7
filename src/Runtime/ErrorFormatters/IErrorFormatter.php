<?php
	/*
	 * Copyright (c) 2017 Kruithne (kruithne@gmail.com)
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

	namespace KrameWork\Runtime\ErrorFormatters;

	use KrameWork\Runtime\ErrorReports\IErrorReport;
	use KrameWork\Runtime\ErrorTypes\IError;

	/**
	 * Interface IErrorFormatter
	 * Represents classes that create error reports.
	 *
	 * @package KrameWork\Runtime\ErrorFormatters
	 * @author Kruithne <kruithne@gmail.com>
	 */
	interface IErrorFormatter
	{
		/**
		 * Called just before this report is used.
		 *
		 * @api beginReport
		 */
		public function beginReport();

		/**
		 * Format a stacktrace and add it to the report.
		 *
		 * @api reportStacktrace
		 * @param array $trace Stacktrace.
		 */
		public function reportStacktrace(array $trace);

		/**
		 * Format an error and add it to the report.
		 *
		 * @api handleError
		 * @param IError $error Error which occurred.
		 */
		public function reportError(IError $error);

		/**
		 * Format debug data and add it to the report.
		 *
		 * @param $debug array Key/Value pairs
		 */
		public function reportDebug(array $debug);

		/**
		 * Format an array and add it to the report.
		 *
		 * @api formatArray
		 * @param string $name Name for the array.
		 * @param array $arr Array of data.
		 */
		public function reportArray(string $name, array $arr);

		/**
		 * Format a data string and add it to the report.
		 *
		 * @api reportString
		 * @param string $name Name of the data string.
		 * @param string $str Data string.
		 */
		public function reportString(string $name, string $str);

		/**
		 * Generate a report.
		 *
		 * @api generate
		 * @return IErrorReport
		 */
		public function generate():IErrorReport;
	}