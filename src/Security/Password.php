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
	namespace KrameWork\Security;

	/**
	 * Class Password
	 * Represents a password.
	 *
	 * @package KrameWork\Security
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class Password
	{
		/**
		 * Password constructor.
		 *
		 * @api __construct
		 * @param string $value Plaintext password.
		 */
		public function __construct(string $value) {
			$this->value = $value;
		}

		/**
		 * Return the password as an MD5 hash.
		 * Note: MD5 hashes are just that, hashes, not encryption.
		 *
		 * @api asMD5Hash
		 * @return string
		 */
		public function asMD5Hash():string {
			return md5($this->value);
		}

		/**
		 * Return a mask of this password.
		 *
		 * @api asMask
		 * @return string
		 */
		public function asMask():string {
			return str_repeat('*', \strlen($this->value));
		}

		/**
		 * Get the plain-text password contained by this object.
		 *
		 * @api __toString
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString():string {
			return $this->value;
		}

		/**
		 * @var string
		 */
		private $value;
	}