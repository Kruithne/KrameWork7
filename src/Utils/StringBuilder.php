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
		 *
		 * @api __construct
		 * @param array $args Initial elements to append to the builder.
		 */
		public function __construct(...$args) {
			$this->clear();
			$this->append($args);
		}

		/**
		 * Append one or more elements to the builder.
		 * Arrays will be recursively iterated with all elements appended.
		 *
		 * @api append
		 * @param array $args Elements to append to the builder.
		 * @return StringBuilder
		 */
		public function append(...$args):StringBuilder {
			foreach ($args as $arg) {
				if (is_array($arg)) {
					foreach ($arg as $subArg)
						$this->append($subArg);
				} else {
					$index = $this->getLineIndex(); // Bottom-most line index.
					$line = $this->data[$index]; // Line data itself.

					// Append a separator first if needed.
					if ($this->separator !== null && \strlen($line) > 0)
						$line .= $this->separator;

					// Indent if needed.
					if ($this->indent > 0)
						$line .= str_repeat("\t", $this->indent);

					// Append new data to the line and update in the stack.
					$this->data[$index] = $line . $arg;
				}
			}
			return $this;
		}

		/**
		 * Append elements to the builder with a line-end prefix/suffix.
		 * Defaults to Unix line-end unless specified using setLineEnd().
		 * Providing a null element is equivalent to calling newLine(true).
		 * Note: One line-end added per function call, not per element.
		 *
		 * @api appendLine
		 * @param string|array|null $line Element(s) to append.
		 * @param bool $suffix Line-end will be suffix, otherwise prefix.
		 * @return StringBuilder
		 */
		public function appendLine($line = null, bool $suffix = true):StringBuilder {
			if ($line === null)
				return $this->newLine(true);

			if ($suffix)
				$this->append($line)->newLine(true);
			else
				$this->newLine(true)->append($line);

			return $this;
		}

		/**
		 * Append a single formatted string to the builder.
		 *
		 * @api appendf
		 * @param string $format String format pattern.
		 * @param array $args Components for the format pattern.
		 * @return StringBuilder
		 */
		public function appendf(string $format, ...$args):StringBuilder {
			array_unshift($args, $format); // Push the format string onto the args.
			return $this->append(\call_user_func_array('sprintf', $args));
		}

		/**
		 * Prepend one or more element to the builder.
		 * Arrays will be recursively iterated with all elements prepended.
		 *
		 * @api prepend
		 * @param array $args Elements to prepend to the builder.
		 * @return StringBuilder
		 */
		public function prepend(...$args):StringBuilder {
			foreach ($args as $arg) {
				if (is_array($arg)) {
					foreach ($arg as $subArg)
						$this->prepend($subArg);
				} else {
					$line = $this->data[0]; // Top-most line.

					// Append separator to the new data.
					if ($this->separator !== null && \strlen($line) > 0)
						$arg .= $this->separator;

					// Indent if needed.
					if ($this->indent > 0)
						$arg .= str_repeat("\t", $this->indent);

					// Prepend new data to the line and update in the stack.
					$this->data[0] = $arg . $line;
				}
			}
			return $this;
		}

		/**
		 * Prepend elements to the builder with a line-end prefix/suffix.
		 * Defaults to Unix line-end unless specified using setLineEnd().
		 * Providing a null element is equivalent to calling newLine(false).
		 * Note: One line-end added per function call, not per element.
		 *
		 * @api prependLine
		 * @param string|array|null $line Element to prepend.
		 * @param bool $suffix Line-end will be suffix, otherwise prefix.
		 * @return StringBuilder
		 */
		public function prependLine($line = null, bool $suffix = true):StringBuilder {
			if ($line === null)
				return $this->newLine(false);

			if ($suffix)
				$this->newline(false)->prepend($line);
			else
				$this->prepend($line)->newline(false);

			return $this;
		}

		/**
		 * Prepend a single formatted string to the builder.
		 *
		 * @api prependf
		 * @param string $format String format pattern.
		 * @param array $args Components for the format pattern.
		 * @return StringBuilder
		 */
		public function prependf(string $format, ...$args):StringBuilder {
			array_unshift($args, $format); // Push the format string onto the args.
			return $this->prepend(\call_user_func_array('sprintf', $args));
		}

		/**
		 * Add an element $count amount of times to the builder.
		 * Arrays will be recursively iterated with each element added.
		 *
		 * @api repeat
		 * @param string|array $input Element to repeat.
		 * @param int $count How many times to append/prepend the element.
		 * @param bool $append Append the element, otherwise prepend.
		 * @return StringBuilder
		 */
		public function repeat($input, int $count = 1, bool $append = true):StringBuilder {
			for ($i = 0; $i < $count; $i++)
				$append ? $this->append($input) : $this->prepend($input);

			return $this;
		}

		/**
		 * Add a single line-end to the builder.
		 * Defaults to Unix line-end unless specified using setLineEnd().
		 *
		 * @api newLine
		 * @param bool $append Append the line-end, otherwise prepend.
		 * @return StringBuilder
		 */
		public function newLine(bool $append = true):StringBuilder {
			$append ? array_push($this->data, '') : array_unshift($this->data, '');
			return $this;
		}

		/**
		 * Clear the builder, resetting it completely and deleting all
		 * elements that have been added.
		 *
		 * @api clear
		 * @return StringBuilder
		 */
		public function clear():StringBuilder {
			$this->data = [''];
			return $this;
		}

		/**
		 * Retrieve the total length of content contained in the builder.
		 *
		 * @api length
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
		 * @api isEmpty
		 * @return bool
		 */
		public function isEmpty():bool {
			return $this->length() == 0;
		}

		/**
		 * Set the separator for the StringBuilder.
		 * Not retroactive; only effects newly appended content.
		 * To disable, supply a null value.
		 *
		 * @api setSeparator
		 * @param string|null $sep Separator character.
		 * @return StringBuilder
		 */
		public function setSeparator($sep):StringBuilder {
			$this->separator = $sep;
			return $this;
		}

		/**
		 * Get the line-end character used by this string builder.
		 *
		 * @api getLineEnd
		 * @return string
		 */
		public function getLineEnd():string {
			return $this->lineEnd ?? self::LE_UNIX;
		}

		/**
		 * Set the line-end character to use in this builder.
		 *
		 * @api setLineEnd
		 * @param string $lineEnd Line-end; check StringBuilder::LE_* constants.
		 * @return StringBuilder
		 */
		public function setLineEnd(string $lineEnd):StringBuilder {
			$this->lineEnd = $lineEnd;
			return $this;
		}

		/**
		 * Return the compiled result of the string builder.
		 *
		 * @api __toString
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
		 * Apply one level of indentation to input.
		 *
		 * @api indent
		 * @param int $value How many levels to indent.
		 * @return StringBuilder
		 */
		public function indent(int $value = 1):StringBuilder {
			$this->indent += $value;
			return $this;
		}

		/**
		 * Remove one level of indentation from input.
		 *
		 * @api outdent
		 * @param int $value How many levels to outdent.
		 * @return StringBuilder
		 */
		public function outdent(int $value = 1):StringBuilder {
			$this->indent = max(0, $this->indent - $value);
			return $this;
		}

		/**
		 * @var int
		 */
		private $indent;

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