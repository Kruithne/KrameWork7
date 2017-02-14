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
	 */
	class ReportRow implements \JsonSerializable
	{
		/**
		 * ReportRow constructor.
		 * @param $data array|Value[] The underlying result row
		 */
		public function __construct($data) {
			$this->data = $data;
		}

		function jsonSerialize() {
			$serialize = [];
			foreach ($this->data as $data) {
				foreach ($data as $field => $value) {
					if ($value instanceof Value)
						$serialize[$field] = $value->json();
					else
						$serialize[$field] = $value;
				}
			}
			return $serialize;
		}

		/**
		 * @var array|Value[] The data contained in the row
		 */
		private $data;
	}