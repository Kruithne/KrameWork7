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
	 * Interface IHTTPHeader
	 * Represents a HTTP header.
	 *
	 * @package KrameWork\Security
	 * @author Kruithne <kruithne@gmail.com>
	 */
	interface IHTTPHeader
	{
		/**
		 * Apply this header to the current response.
		 *
		 * @api apply
		 */
		public function apply();

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName():string;

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue():string;

		/**
		 * Get the compiled header string.
		 *
		 * @api __toString()
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		public function __toString():string;
	}