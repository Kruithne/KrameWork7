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

	namespace KrameWork\Runtime\ErrorTypes;

	/**
	 * Interface IError
	 * Represents classes that contain error data.
	 *
	 * @package KrameWork\Runtime\ErrorTypes
	 * @author Kruithne <kruithne@gmail.com>
	 */
	interface IError
	{
		/**
		 * Get the message associated with this error.
		 *
		 * @api getMessage
		 * @return string
		 */
		public function getMessage():string;

		/**
		 * Get the line of code that produced this error.
		 *
		 * @api getLine
		 * @return int
		 */
		public function getLine():int;

		/**
		 * Get the file which produced this error,
		 *
		 * @api getFile
		 * @return string
		 */
		public function getFile():string;

		/**
		 * Get the stacktrace for this error.
		 *
		 * @api getTrace
		 * @return array
		 */
		public function getTrace():array;

		/**
		 * Get a pretty name for this error.
		 *
		 * @api getName
		 * @return string
		 */
		public function getName():string;

		/**
		 * Get a prefix for this error type.
		 *
		 * @api getPrefix
		 * @return string
		 */
		public function getPrefix():string;

		/**
		 * Get additional debugging data from the error source.
		 * 
		 * @api getDebugData
		 * @return mixed
		 */
		public function getDebugData();
	}