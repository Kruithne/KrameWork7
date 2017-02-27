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
	namespace KrameWork\HTTP;

	/**
	 * Class ReferrerPolicyHeader
	 * HTTP Referrer Policy Header.
	 *
	 * @package KrameWork\HTTP
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class ReferrerPolicyHeader extends HTTPHeader
	{
		const EMPTY = '';
		const ORIGIN = 'origin';
		const UNSAFE_URL = 'unsafe-url';
		const NO_REFERRER = 'no-referrer';
		const SAME_ORIGIN = 'same-origin';
		const STRICT_ORIGIN = 'strict-origin';
		const ORIGIN_CROSS_ORIGIN = 'origin-when-cross-origin';
		const NO_REFERRER_DOWNGRADE = 'no-referrer-when-downgrade';
		const STRICT_CROSS_ORIGIN = 'strict-origin-when-cross-origin';

		/**
		 * ReferrerPolicyHeader constructor.
		 *
		 * @api __construct
		 * @param string $value Initial value, use class constants.
		 */
		public function __construct(string $value = self::EMPTY) {
			$this->value = $value;
		}

		/**
		 * Set the value of this header.
		 * Use the constants provided by the ReferrerPolicyHeader class.
		 *
		 * @api setValue
		 * @param string $value Value for the header.
		 */
		public function setValue(string $value) {
			$this->value = $value;
		}

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName(): string {
			return 'Referrer-Policy';
		}

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue(): string {
			return $this->value;
		}

		/**
		 * @var string
		 */
		private $value;
	}