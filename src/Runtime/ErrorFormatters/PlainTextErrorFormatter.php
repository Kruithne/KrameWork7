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

	use KrameWork\Runtime\ErrorTypes\IError;
	use KrameWork\Utils\StringBuilder;

	require_once(__DIR__ . '/../../Utils/StringBuilder.php');
	require_once(__DIR__ . '/IErrorReport.php');

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
		public function __construct(string $lineEnd = StringBuilder::LE_UNIX, bool $wrapPreTags  = false) {
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
		}

		/**
		 * Format a data string and add it to the report.
		 *
		 * @api reportString
		 * @param string $name Name of the data string.
		 * @param string $str Data string.
		 */
		public function reportString(string $name, string $str) {
			$this->report->appendf('> %s => %s', $name, $this->getVariableString($str));
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
			if (count($arr)) {
				$this->report->appendf('> %s [%s items]', $name, count($arr))->newLine()->indent();
				foreach ($arr as $key => $value)
					$this->report->appendf('%s => %s', $key, $this->getVariableString($value))->newLine();
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
			$this->report->appendLine($error->getPrefix() . ' : ' . $error->getName())->indent();
			$this->report->appendLine('> Message: ' . $error->getMessage());
			$this->report->appendf('> Occurred: %s (%s)', date(DATE_RFC2822), time())->newLine();
			$this->report->appendf('> Script: %s (Line %s)', $error->getFile(), $error->getLine());
			$this->formatStacktrace($error->getTrace());
		}

		/**
		 * Compile the report into a string.
		 *
		 * @api __toString
		 * @return string
		 */
		public function __toString(): string {
			$this->report->outdent()->newLine();
			$this->report->appendf('Report generated automatically on %s (%s).', date(DATE_RFC2822), time());

			if ($this->wrapPreTags)
				$this->report->prepend('<pre>')->append('</pre>');

			return $this->report;
		}

		/**
		 * Format the given stacktrace for the report.
		 *
		 * @internal
		 * @param array $trace Stacktrace to format.
		 */
		private function formatStacktrace($trace) {
			$this->report->newLine()->newLine();
			$this->report->appendf('> Stack trace [%s steps]:', count($trace))->indent()->newLine();
			foreach($trace as $node) {
				$args = [];
				foreach ($node['args'] ?? [] as $key => $arg)
					$args[$key] = $this->getVariableString($arg);

				$this->report->appendf(
					'%s:%s - %s%s%s(%s)',
					$node['file'] ?? 'interpreter',
					$node['line'] ?? '?',
					$node['class'] ?? '',
					$node['type'] ?? '',
					$node['function'] ?? '',
					implode(', ', $args)
				)->newLine();
			}
			$this->report->outdent()->newline();
		}

		/**
		 * Get a pretty representation of a variable.
		 *
		 * @internal
		 * @param mixed $var Variable to represent.
		 * @return string
		 */
		private function getVariableString($var):string {
			$type = gettype($var);
			if ($type == 'object') {
				$type = get_class($var);
				if (!method_exists($var, '__toString'))
					$var = $type . ' instance';

			} elseif ($type == 'string') {
				$length = \strlen($var);
				$var = "({$length}) \"{$var}\"";
			} elseif ($type == 'array') {
				$var = count($var) . ' items';
			}

			return "({$type}) {$var}";
		}

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