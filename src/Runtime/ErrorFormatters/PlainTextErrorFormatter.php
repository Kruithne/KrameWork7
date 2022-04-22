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

	use KrameWork\Runtime\ErrorReports\ErrorReport;
	use KrameWork\Runtime\ErrorReports\IErrorReport;
	use KrameWork\Runtime\ErrorTypes\IError;
	use KrameWork\Timing\Timer;
	use KrameWork\Utils\StringBuilder;
	use Kramework\Utils\StringUtil;

	require_once(__DIR__ . '/../../Utils/StringBuilder.php');
	require_once(__DIR__ . '/../../Utils/StringUtil.php');
	require_once(__DIR__ . '/../ErrorReports/ErrorReport.php');
	require_once(__DIR__ . '/IErrorFormatter.php');
	require_once(__DIR__ . '/../../Timing/Timer.php');

	/**
	 * Class PlainTextErrorFormatter
	 * Error report implementation: Plain Text
	 *
	 * @package KrameWork\Runtime\ErrorReports
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class PlainTextErrorFormatter implements IErrorFormatter
	{
		/**
		 * PlainTextErrorFormatter constructor.
		 *
		 * @api __construct
		 * @param string $lineEnd Line-endings to use.
		 * @param bool $wrapPreTags Wrap the report in HTML <pre/> tags.
		 */
		public function __construct(string $lineEnd = StringBuilder::LE_UNIX, bool $wrapPreTags = false) {
			$this->timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$this->wrapPreTags = $wrapPreTags;
			$this->lineEnd = $lineEnd;
		}

		/**
		 * Called just before this report is used.
		 *
		 * @api beginReport
		 */
		public function beginReport() {
			$this->report = new StringBuilder();
			$this->report->setLineEnd($this->lineEnd);
			$this->timer->start();
		}

		/**
		 * Format a data string and add it to the report.
		 *
		 * @api reportString
		 * @param string $name Name of the data string.
		 * @param string $str Data string.
		 */
		public function reportString(string $name, string $str) {
			$this->report->appendf('> %s => %s', $name, StringUtil::variableAsString($str));
			$this->report->newLine()->newLine();
		}

		/**
		 * Format an array and add it to the report.
		 *
		 * @api formatArray
		 * @param string $name Name for the array.
		 * @param array $arr Array of data.
		 */
		public function reportArray(string $name, array $arr) {
			if ($arr) {
				$this->report->appendf('> %s [%s items]', $name, \count($arr))->newLine()->indent();
				foreach ($arr as $key => $value)
					$this->report->appendf('%s => %s', $key, StringUtil::variableAsString($value))->newLine();
			} else {
				$this->report->appendf('> %s [empty]', $name)->newLine();
				$this->report->indent()->appendLine('No data to display');
			}

			$this->report->outdent()->newLine();
		}

		/**
		 * Format a report for a runtime error.
		 *
		 * @api handleError
		 * @param IError $error Error which occurred.
		 */
		public function reportError(IError $error) {
			$this->error = $error;
			$this->report->appendLine($error->getPrefix() . ' : ' . $error->getName())->indent();
			$this->report->appendLine('> Server: ' . \php_uname());
			$this->report->appendLine('> Message: ' . $error->getMessage());
			$this->report->appendf('> Occurred: %$2s (%$1s)', $t = \time(), \date(\DATE_RFC2822, $t))->newLine();
			$this->report->appendf('> Script: %s (Line %s)', $error->getFile(), $error->getLine());

			$debug = $error->getDebugData();
			if ($debug !== null) {
				$this->report->newline()->newline();
				$this->report->appendf('> Exception debug data:')->indent()->newline();
				$this->report->appendf('> Debug: %s', \var_export($debug, true));
			}
		}

		/**
		 * Format debug data and add it to the report.
		 *
		 * @param $debug array Key/Value pairs
		 */
		public function reportDebug(array $debug)
		{
			$this->report->newLine()->newLine();
			$this->report->appendf('> Application debug data:')->indent()->newLine();
			foreach ($debug as $key => $value) {
				$this->report->appendf('%s = %s', $key, $value)->newLine();
			}
			$this->report->outdent()->newLine();
		}

		/**
		 * Format a stacktrace and add it to the report.
		 *
		 * @api reportStacktrace
		 * @param array $trace Stacktrace.
		 */
		public function reportStacktrace(array $trace) {
			$this->report->newLine()->newLine();
			$this->report->appendf('> Stack trace [%s steps]:', \count($trace))->indent()->newLine();
			foreach($trace as $node) {
				$args = [];
				foreach ($node['args'] ?? [] as $key => $arg)
					$args[$key] = StringUtil::variableAsString($arg);

				$this->report->appendf(
					'%s:%s - %s%s%s(%s)',
					$node['file'] ?? 'interpreter',
					$node['line'] ?? '?',
					$node['class'] ?? '',
					$node['type'] ?? '',
					$node['function'] ?? '',
					\implode(', ', $args)
				)->newLine();
			}
			$this->report->outdent()->newline();
		}

		/**
		 * Get the content-type of this error report.
		 *
		 * @api getContentType
		 * @return string
		 */
		public function getContentType(): string {
			return ($this->wrapPreTags ? 'text/html' : 'text/plain') . '; charset=utf-8';
		}

		/**
		 * Get the extension to use when this report is stored to a file.
		 *
		 * @api getExtension
		 * @return string
		 */
		public function getExtension(): string {
			return $this->wrapPreTags ? '.html' : '.log';
		}

		/**
		 * Compile the report into a string.
		 *
		 * @api __toString
		 * @return string
		 */
		public function __toString(): string {
			$this->report->outdent()->newLine();
			$this->report->appendf('Report generated automatically on %$2s (%$1s).', $t = \time(), \date(\DATE_RFC2822, $t));

			if ($this->wrapPreTags)
				$this->report->prepend('<pre>')->append('</pre>');

			return $this->report;
		}

		/**
		 * Generate a report.
		 *
		 * @api generate
		 * @return IErrorReport
		 */
		public function generate():IErrorReport {
			$this->report->outdent()->newLine();
			$this->report->appendf('Report generated automatically on %$2s (%$1s).', $t = \time(), \date(\DATE_RFC822, $t));

			if ($this->wrapPreTags)
				$this->report->prepend('<pre>')->append('</pre>');

			$contentType = ($this->wrapPreTags ? 'text/html' : 'text/plain') . '; charset=utf-8';
			$extension = $this->wrapPreTags ? '.html' : '.log';

			$this->timer->stop();
			$report = $this->report . $this->timer->format($this->lineEnd . 'Generated in %.4fs.');

			return new ErrorReport($this->error, $contentType, $extension, $report);
		}

		/**
		 * @var Timer
		 */
		protected $timer;

		/**
		 * @var IError
		 */
		protected $error;

		/**
		 * @var StringBuilder
		 */
		protected $report;

		/**
		 * @var bool
		 */
		protected $wrapPreTags;

		/**
		 * @var string
		 */
		protected $lineEnd;
	}