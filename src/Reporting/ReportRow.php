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

	namespace KrameWork\Reporting;

	use KrameWork\Data\Value;

	/**
	 * Class ResultRow
	 * Encapsulates a result row of formatted data, with support for JSON serialization
	 * @author docpify <morten@runsafe.no>
	 */
	class ReportRow implements \JsonSerializable, \Iterator, \ArrayAccess
	{
		/**
		 * ReportRow constructor.
		 * @param $data object The underlying result row
		 */
		public function __construct($data) {
			$this->data = $data;
		}

		function jsonSerialize() {
			$serialize = [];
			foreach ($this->data as $field => $value) {
				if ($value instanceof Value)
					$serialize[$field] = $value->json();
				else
					$serialize[$field] = $value;
			}
			return $serialize;
		}

		/**
		 * @link http://php.net/manual/en/iterator.current.php
		 */
		public function current() {
			return $this->data[$this->key()];
		}

		/**
		 * @link http://php.net/manual/en/iterator.next.php
		 */
		public function next() {
			$this->position++;
		}

		/**
		 * @link http://php.net/manual/en/iterator.key.php
		 */
		public function key() {
			if ($this->keys == null)
				$this->keys = \get_object_vars($this->data);
			return $this->keys[$this->position];
		}

		/**
		 * @link http://php.net/manual/en/iterator.valid.php
		 */
		public function valid() {
			if ($this->keys == null)
				$this->keys = \get_object_vars($this->data);
			return $this->data && \count($this->keys) > $this->position;
		}

		/**
		 * @link http://php.net/manual/en/iterator.rewind.php
		 */
		public function rewind() {
			$this->position = 0;
		}

		/**
		 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
		 */
		public function offsetExists($offset) {
			return $this->data && isset($this->data[$offset]);
		}

		/**
		 * @link http://php.net/manual/en/arrayaccess.offsetget.php
		 */
		public function offsetGet($offset) {
			return $this->data[$offset];
		}

		/**
		 * @link http://php.net/manual/en/arrayaccess.offsetset.php
		 */
		public function offsetSet($offset, $value) {
			if (!isset($this->data[$offset]))
				$this->keys = null;
			$this->data[$offset] = $value;
		}

		/**
		 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
		 */
		public function offsetUnset($offset) {
			unset($this->data[$offset]);
		}

		/**
		 * @var array|null Numerically indexed keys for iteration purposes
		 */
		private $keys;

		/**
		 * @var object The data contained in the row
		 */
		private $data;

		/**
		 * @var int
		 */
		private $position = 0;
	}
