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
	require_once(__DIR__ . '/HTTPHeader.php');

	/**
	 * Class XFrameHeader
	 * X-Framing-Option header, for use with CSP frame-ancestors directive.
	 *
	 * @package KrameWork\Security\HTTP
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class XFrameHeader extends HTTPHeader
	{
		const DENY = 'DENY';
		const SAME_ORIGIN = 'SAMEORIGIN';

		/**
		 * XFrameHeader constructor.
		 *
		 * @api __construct
		 * @param string $option Framing option for this header.
		 */
		public function __construct(string $option = self::DENY) {
			$this->option = $option;
		}

		/**
		 * Set the framing option for this header.
		 * Use the constants provided by the XFrameHeader class.
		 *
		 * @api setOption
		 * @param string $option Directive for this header.
		 */
		public function setOption(string $option) {
			$this->option = $option;
		}

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName(): string {
			return 'X-Frame-Options';
		}

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue(): string {
			return $this->option;
		}

		/**
		 * @var string
		 */
		protected $option;
	}