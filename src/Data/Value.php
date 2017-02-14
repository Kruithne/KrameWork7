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

	namespace KrameWork\Data;

	/**
	 * Class Value
	 * A generic value
	 * @author docpify <morten@runsafe.no>
	 */
	class Value
	{
		/**
		 * Value constructor.
		 * @param $value mixed The encapsulated value
		 */
		public function __construct($value) {
			$this->value = $value;
		}

		/**
		 * @return mixed The encapsulated value
		 */
		public function real() {
			return $this->value;
		}

		/**
		 * @return mixed The value to be encoded into JSON
		 */
		public function json() {
			return $this->value;
		}

		/**
		 * Utility function for sorting a list of values
		 * @param $to Value|mixed A value to compare against
		 * @return int Sorting index
		 */
		public function compare($to) {
			$a = $this->value;
			if ($to instanceof Value)
				$b = $to->real();
			else
				$b = $to;

			if ($b === null)
				return $a === null ? 0 : 1;
			if ($a === null)
				return -1;

			return strnatcasecmp((string)$a, (string)$b);
		}

		/**
		 * @return string The encapsulated value as presented by a string
		 */
		public function __toString() {
			return (string)$this->value;
		}

		/**
		 * @var mixed The encapsulated value
		 */
		protected $value;
	}