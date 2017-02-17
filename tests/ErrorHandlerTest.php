<?php
	use KrameWork\Runtime\ErrorFormatters\IErrorFormatter;
	use KrameWork\Runtime\ErrorDispatchers\IErrorDispatcher;
	use KrameWork\Runtime\ErrorHandler;
	use KrameWork\Runtime\ErrorTypes\IError;

	require_once(__DIR__ . '/../src/Runtime/ErrorHandler.php');
	require_once(__DIR__ . '/../src/Runtime/ErrorFormatters/IErrorReport.php');
	require_once(__DIR__ . '/../src/Runtime/ErrorDispatchers/IErrorDispatcher.php');

	class ErrorHandlerTestException extends \Exception {}

	class ErrorHandlerTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Sets up the fixture, for example, open a network connection.
		 * This method is called before a test is executed.
		 */
		protected function setUp() {
			// Custom built error report for the purpose of this unit test.
			$this->formatter = new class implements IErrorFormatter {
				/**
				 * Called just before this report is used.
				 *
				 * @api beginReport
				 */
				public function beginReport() {
					$this->str = '';
				}

				/**
				 * Format an error and add it to the report.
				 *
				 * @api handleError
				 * @param IError $error Error which occurred.
				 */
				public function reportError(IError $error) {
					$parts = [$error->getPrefix(), $error->getName(), $error->getLine(), $error->getMessage(), $error->getFile()];
					$this->str = implode(" ", $parts);
				}

				/**
				 * Format an array and add it to the report.
				 *
				 * @api formatArray
				 * @param string $name Name for the array.
				 * @param array $arr Array of data.
				 */
				public function reportArray(string $name, array $arr) {
					// Dummy.
				}

				/**
				 * Format a data string and add it to the report.
				 *
				 * @api reportString
				 * @param string $name Name of the data string.
				 * @param string $str Data string.
				 */
				public function reportString(string $name, string $str) {
					// Dummy
				}

				/**
				 * Compile the report into a string.
				 *
				 * @api __toString
				 * @return string
				 */
				public function __toString(): string {
					return $this->str;
				}

				private $str;
			};

			// Custom built dispatcher for the purpose of this unit test.
			$this->dispatcher = new class implements IErrorDispatcher
			{
				/**
				 * Dispatch an error report.
				 *
				 * @api dispatch
				 * @param string $report Report to dispatch.
				 */
				public function dispatch(string $report) {
					$GLOBALS['__dispatchedError'] = $report;
				}
			};
		}

		/**
		 * Test if the error handler controls states as expected.
		 */
		public function testState() {
			// Obtain the current states of error/exception handlers.
			$origErrorHandler = set_error_handler(null);
			$origExceptionHandler = set_exception_handler(null);
			$errorLevel = error_reporting();

			// Restore those two states to keep a clean stack.
			restore_error_handler();
			restore_exception_handler();

			$handler = new ErrorHandler($this->formatter, $this->dispatcher);
			$this->assertEquals(E_ALL, error_reporting()); // Ensure error state changed to what we expected.
			$handler->deactivate(); // Deactivate the error handler.

			// Obtain the states of the error/exception handlers again for evaluation.
			$errorHandler = set_error_handler(null);
			$exceptionHandler = set_exception_handler(null);

			// Restore those two states to keep a clean stack.
			restore_error_handler();
			restore_exception_handler();

			$this->assertEquals($origErrorHandler, $errorHandler);
			$this->assertEquals($origExceptionHandler, $exceptionHandler);
			$this->assertEquals($errorLevel, error_reporting());

			unset($handler);
		}

		/**
		 * Test runtime errors are caught properly.
		 */
		public function testErrorCatching() {
			unset($GLOBALS['__dispatchedError']);
			$handler = new ErrorHandler($this->formatter, $this->dispatcher);

			user_error('Unit testing error.', E_USER_ERROR);
			$expected = sprintf('RUNTIME ERROR USER ERROR %s Unit testing error. %s', __LINE__ - 1, __FILE__);
			$this->assertEquals($expected, $GLOBALS['__dispatchedError']);

			$handler->deactivate();
			unset($handler, $GLOBALS['__dispatchedError']);
		}

		/**
		 * Test exceptions are caught properly.
		 */
		public function testExceptionCatching() {
			unset($GLOBALS['__dispatchedError']);
			$handler = new ErrorHandler($this->formatter, $this->dispatcher);

			$this->expectException('ErrorHandlerTestException');
			throw new ErrorHandlerTestException('Unit testing exception.');
			$expected = sprintf('EXCEPTION ErrorHandlerTestException %s Unit testing exception. %s', __LINE__ - 1, __FILE__);
			$this->assertEquals($expected, $GLOBALS['__dispatchedError']);

			$handler->deactivate();
			unset($handler, $GLOBALS['__dispatchedError']);
		}

		private $formatter;
		private $dispatcher;
	}