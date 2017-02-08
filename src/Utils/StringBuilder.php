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

	namespace KrameWork\Utils;

	/**
	 * Class StringBuilder
	 * Fluent API String Builder.
	 *
	 * @package KrameWork\Utils
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class StringBuilder
	{
		const LE_WIN = "\r\n";
		const LE_DARWIN = "\r";
		const LE_UNIX = "\n";

		/**
		 * StringBuilder constructor.
		 * Accepts variable arguments of strings, arrays and objects.
		 *
		 * @api
		 */
		public function __construct() {
			$this->clear();
			$this->append(func_get_args());
		}

		/**
		 * Append string(s) to the builder.
		 * Accepts variable arguments of strings, arrays or objects.
		 *
		 * @api
		 * @return StringBuilder
		 */
		public function append():StringBuilder {
			foreach (func_get_args() as $arg) {
				if (is_array($arg)) {
					foreach ($arg as $subArg)
						$this->append($subArg);
				} else {
					$index = $this->getLineIndex(); // Bottom-most line index.
					$line = $this->data[$index]; // Line data itself.

					// Append a separator first if needed.
					if ($this->separator !== null && \strlen($line) > 0)
						$line .= $this->separator;

					// Append new data to the line and update in the stack.
					$this->data[$index] = $line . $arg;
				}
			}
			return $this;
		}

		/**
		 * Append a line-end terminated/prefixed string to this builder.
		 *
		 * @api
		 * @param string|null $line String to append.
		 * @param bool $trailLineEnd Terminate string with line-end, otherwise prefix it.
		 * @return StringBuilder
		 */
		public function appendLine($line = null, bool $trailLineEnd = true):StringBuilder {
			if ($line === null)
				return $this->newLine(true);

			if ($trailLineEnd)
				$this->append($line)->newLine(true);
			else
				$this->newLine(true)->append($line);

			return $this;
		}

		/**
		 * Append a formatted string to the builder.
		 *
		 * @api
		 * @param string $format Format pattern.
		 * @return StringBuilder
		 */
		public function appendf(string $format):StringBuilder {
			return $this->append(\call_user_func_array('sprintf', func_get_args()));
		}

		/**
		 * Prepend string(s) to the builder.
		 * Accepts variable arguments of strings, arrays or objects.
		 *
		 * @api
		 * @return StringBuilder
		 */
		public function prepend():StringBuilder {
			foreach (func_get_args() as $arg) {
				if (is_array($arg)) {
					foreach ($arg as $subArg)
						$this->prepend($subArg);
				} else {
					$line = $this->data[0]; // Top-most line.

					// Append separator to the new data.
					if ($this->separator !== null && \strlen($line) > 0)
						$arg .= $this->separator;

					// Prepend new data to the line and update in the stack.
					$this->data[0] = $arg . $line;
				}
			}
			return $this;
		}

		/**
		 * Prepend a line-end terminated/prefixed string to this builder.
		 *
		 * @api
		 * @param string|null $line String to prepend.
		 * @param bool $trailLineEnd Terminate string with line-end, otherwise prefix it.
		 * @return StringBuilder
		 */
		public function prependLine($line = null, bool $trailLineEnd = true):StringBuilder {
			if ($line === null)
				return $this->newLine(false);

			if ($trailLineEnd)
				$this->newline(false)->prepend($line);
			else
				$this->prepend($line)->newline(false);

			return $this;
		}

		/**
		 * Prepend a formatted string to the builder.
		 *
		 * @api
		 * @param string $format Format pattern.
		 * @return StringBuilder
		 */
		public function prependf(string $format):StringBuilder {
			return $this->prepend(\call_user_func_array('sprintf', func_get_args()));
		}

		/**
		 * Append/prepend a string $count amount of times.
		 *
		 * @api
		 * @param mixed $input Object that can be cast to a string.
		 * @param int $count How many times to append/prepend the string.
		 * @param bool $append True = Append, False = Prepend.
		 * @return StringBuilder
		 */
		public function repeat($input, $count = 1, bool $append = true):StringBuilder {
			for ($i = 0; $i < $count; $i++)
				$append ? $this->append($input) : $this->prepend($input);

			return $this;
		}

		/**
		 * Add a line-end to the string.
		 *
		 * @api
		 * @param bool $append Append, otherwise prepend.
		 * @return StringBuilder
		 */
		public function newLine(bool $append = true):StringBuilder {
			$append ? array_push($this->data, '') : array_unshift($this->data, '');
			return $this;
		}

		/**
		 * Clear any input data to the string builder.
		 *
		 * @api
		 * @return StringBuilder
		 */
		public function clear():StringBuilder {
			$this->data = [''];
			return $this;
		}

		/**
		 * Retrieve the length of the builder string.
		 *
		 * @api
		 * @return int
		 */
		public function length():int {
			$len = 0;
			foreach ($this->data as $line)
				$len += strlen($line);

			return $len;
		}

		/**
		 * Check if the builder is empty.
		 *
		 * @api
		 * @return bool
		 */
		public function isEmpty():bool {
			return $this->length() == 0;
		}

		/**
		 * Set the separator for the StringBuilder.
		 * Not retroactive; only effects newly appended content.
		 * To disable, set separator value to null.
		 *
		 * @api
		 * @param string|null $sep
		 * @return StringBuilder
		 */
		public function setSeparator($sep):StringBuilder {
			$this->separator = $sep;
			return $this;
		}

		/**
		 * Get the line-end used by this string builder.
		 *
		 * @api
		 * @return string
		 */
		public function getLineEnd():string {
			return $this->lineEnd ?? self::LE_UNIX;
		}

		/**
		 * Set the line-ending to use for new-line operations.
		 *
		 * @api
		 * @param string $lineEnd Line-end; check StringBuilder constants.
		 * @return StringBuilder
		 */
		public function setLineEnd(string $lineEnd):StringBuilder {
			$this->lineEnd = $lineEnd;
			return $this;
		}

		/**
		 * Return the compiled result of the string builder.
		 *
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		public function __toString() {
			return implode($this->getLineEnd(), $this->data);
		}

		/**
		 * Get the bottom-most line index of the data stack.
		 *
		 * @internal
		 * @return int
		 */
		private function getLineIndex() {
			return count($this->data) - 1;
		}

		/**
		 * @var array
		 */
		private $data;

		/**
		 * @var string|null
		 */
		private $separator;

		/**
		 * @var string|null
		 */
		private $lineEnd;
	}