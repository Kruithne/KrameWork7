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

	/**
	 * @property string label
	 * @property string type
	 * @author docpify <morten@runsafe.no>
	 */
	final class ReportColumn implements \JsonSerializable
	{
		const COL_NONE = '';
		const COL_STRING = 'String';
		const COL_DECIMAL = 'Decimal';
		const COL_INTEGER = 'Integer';
		const COL_DATETIME = 'Datetime';
		const COL_DATE = 'Date';
		const COL_CUSTOM = 'Custom';
		const COL_CURRENCY = 'Currency';

		/**
		 * SQLReportColumn constructor.
		 * @param string $label Column label, as presented to the user
		 * @param string $type Column type, use SQLReportColumn COL_ constants.
		 * @throws \Exception
		 */
		public function __construct(string $label, $type = self::COL_STRING) {
			switch ($type) {
				case self::COL_NONE:
				case self::COL_STRING:
				case self::COL_DECIMAL:
				case self::COL_INTEGER:
				case self::COL_DATETIME:
				case self::COL_DATE:
				case self::COL_CUSTOM:
				case self::COL_CURRENCY:
					break;
				default:
					throw new \Exception('Invalid type "' . $type . '" specified for column');
			}

			$this->label = $label;
			$this->type = $type;
		}

		public $label;
		public $type;

		public function jsonSerialize() {
			return ['Label' => $this->label, 'Type' => $this->type];
		}
	}