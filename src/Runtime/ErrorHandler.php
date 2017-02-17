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

	namespace KrameWork\Runtime;

	use Kramework\Runtime\ErrorDispatchers\IErrorDispatcher;
	use KrameWork\Runtime\ErrorFormatters\IErrorFormatter;
	use KrameWork\Runtime\ErrorTypes\ExceptionError;
	use KrameWork\Runtime\ErrorTypes\RuntimeError;

	require_once(__DIR__ . '/ErrorTypes/ExceptionError.php');
	require_once(__DIR__ . '/ErrorTypes/RuntimeError.php');

	/**
	 * Class ErrorHandler
	 * Module in charge of intercepting, formatting and dispatching errors.
	 *
	 * @package KrameWork\Runtime
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class ErrorHandler
	{
		/**
		 * ErrorHandler constructor.
		 *
		 * @api __construct
		 * @param IErrorFormatter $report Report class used to format error reports.
		 * @param IErrorDispatcher $dispatch Dispatch used to output errors.
		 */
		public function __construct(IErrorFormatter $report, IErrorDispatcher $dispatch) {
			$this->report = $report;
			$this->dispatcher = $dispatch;

			$this->previousErrorLevel = error_reporting();
			error_reporting(E_ALL);

			$this->previousErrorHandler = set_error_handler([$this, 'catchRuntimeError']);
			$this->previousExceptionHandler = set_exception_handler([$this, 'catchException']);

			$this->active = true;
		}

		/**
		 * Catches a normal error thrown during runtime.
		 *
		 * @internal
		 * @param int $type Type of error that occurred.
		 * @param string $message Message describing the error.
		 * @param string $file File which the error occurred in.
		 * @param int $line Line of code the error occurred on.
		 */
		public function catchRuntimeError($type, $message, $file, $line) {
			if (!$this->active)
				return;

			$this->report->beginReport();
			$this->report->reportError(new RuntimeError($type, $message, $file, $line));
			$this->packReport();
			$this->dispatcher->dispatch($this->report);
		}

		/**
		 * Catches an exception thrown during runtime.
		 *
		 * @internal
		 * @param \Exception $exception The exception which occurred.
		 */
		public function catchException(\Exception $exception) {
			if (!$this->active)
				return;

			$this->report->beginReport();
			$this->report->reportError(new ExceptionError($exception));
			$this->packReport();
			$this->dispatcher->dispatch($this->report);
		}

		/**
		 * Disable this error handler, restoring handlers/levels to their
		 * state when this error handler was created.
		 *
		 * @api deactivate
		 */
		public function deactivate() {
			$this->active = false;
			set_error_handler($this->previousErrorHandler);
			set_exception_handler($this->previousExceptionHandler);
			error_reporting($this->previousErrorLevel);
			unset($this->report, $this->dispatcher);
		}

		/**
		 * Pack the report with data useful for debugging.
		 *
		 * @internal
		 */
		private function packReport() {
			// CLI arguments, if available.
			if (php_sapi_name() == 'cli') {
				global $argv;
				$this->report->reportArray('CLI Arguments', $argv);
			}

			// Add server/request data.
			$this->report->reportArray('$_SERVER', $_SERVER); // Server data.
			$this->report->reportArray('$GLOBALS', $GLOBALS); // Global data.
			$this->report->reportArray('$_POST', $_POST); // POST data.
			$this->report->reportArray('$_GET', $_GET); // GET data.
			$this->report->reportArray('$_COOKIE', $_COOKIE); // Delicious cookies.
			$this->report->reportArray('$_FILES', $_FILES); // Files
			$this->report->reportString('Raw Request Content', file_get_contents('php://input')); // Raw request content.
		}

		/**
		 * @var bool
		 */
		protected $active;

		/**
		 * @var IErrorFormatter
		 */
		protected $report;

		/**
		 * @var IErrorDispatcher
		 */
		protected $dispatcher;

		/**
		 * @var string
		 */
		protected $previousErrorHandler;

		/**
		 * @var string
		 */
		protected $previousExceptionHandler;

		/**
		 * @var int
		 */
		protected $previousErrorLevel;
	}