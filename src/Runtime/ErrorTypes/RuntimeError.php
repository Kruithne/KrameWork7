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

	require_once(__DIR__ . '/IError.php');

	/**
	 * Class RuntimeError
	 * Represents a runtime error that occurred.
	 *
	 * @package KrameWork\Runtime
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class RuntimeError implements IError
	{
		/**
		 * RuntimeError constructor.
		 *
		 * @api __construct
		 * @param int $type Type of error that occurred.
		 * @param string $message Description of the error.
		 * @param string $file File where the error occurred.
		 * @param int $line Line of code the error occurred.
		 */
		public function __construct(int $type, string $message, string $file, int $line) {
			$this->type = $type;
			$this->message = $message;
			$this->file = $file;
			$this->line = $line;
		}

		/**
		 * Get a prefix for this error type.
		 *
		 * @api getPrefix
		 * @return string
		 */
		public function getPrefix():string {
			return 'RUNTIME ERROR';
		}

		/**
		 * Get the type of error which occurred.
		 *
		 * @api getType
		 * @return int
		 */
		public function getType():int {
			return $this->type;
		}

		/**
		 * Get the type of error that occurred as a string.
		 *
		 * @api getTypeName
		 * @return string
		 */
		public function getName():string {
			switch ($this->type)
			{
				case E_ERROR:				return 'ERROR';
				case E_WARNING:				return 'WARNING';
				case E_PARSE:				return 'PARSE';
				case E_NOTICE:				return 'NOTICE';
				case E_CORE_ERROR:			return 'CORE ERROR';
				case E_CORE_WARNING:		return 'CORE WARNING';
				case E_COMPILE_ERROR:		return 'COMPILE ERROR';
				case E_COMPILE_WARNING:		return 'COMPILE WARNING';
				case E_USER_ERROR:			return 'USER ERROR';
				case E_USER_WARNING:		return 'USER WARNING';
				case E_USER_NOTICE:			return 'USER NOTICE';
				case E_USER_DEPRECATED:		return 'DEPRECATED';
				case E_STRICT:				return 'STRICT';
				case E_DEPRECATED:			return 'DEPRECATED';
				case E_RECOVERABLE_ERROR:	return 'RECOVERABLE';
				default:					return 'UNKNOWN';
			}
		}

		/**
		 * Get the message provided by this error.
		 *
		 * @api getMessage
		 * @return string
		 */
		public function getMessage():string {
			return $this->message;
		}

		/**
		 * Get the file which this error occurred in.
		 *
		 * @api getFile
		 * @return string
		 */
		public function getFile():string {
			return $this->file;
		}

		/**
		 * Get the line of code this error occurred on.
		 *
		 * @api getLine
		 * @return int
		 */
		public function getLine():int {
			return $this->line;
		}

		/**
		 * Get the stacktrace for this error.
		 *
		 * @api getTrace
		 * @return array
		 */
		public function getTrace(): array {
			return debug_backtrace();
		}

		/**
		 * @var int
		 */
		protected $type;

		/**
		 * @var string
		 */
		protected $message;

		/**
		 * @var string
		 */
		protected $file;

		/**
		 * @var int
		 */
		protected $line;
	}