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

	namespace KrameWork\Mailing;

	/**
	 * Class EncodedContent
	 * Encoded content container.
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class EncodedContent
	{
		const E_BASE64 = 'base64';
		const E_QUOTED_PRINTABLE = 'quoted-printable';
		const E_7BIT = '7bit';

		/**
		 * EncodedContent constructor.
		 *
		 * @api __construct
		 * @param string $encoding Content encoding.
		 */
		public function __construct(string $encoding = self::E_7BIT) {
			$this->encoding = $encoding;
		}

		/**
		 * Set the content of this container.
		 *
		 * @api setContent
		 * @param string $content Content data.
		 */
		public function setContent($content) {
			$this->content = $content;
		}

		/**
		 * Check if this container has content.
		 *
		 * @api hasContent
		 * @return bool
		 */
		public function hasContent():bool {
			return $this->content && strlen($this->content) > 0;
		}

		/**
		 * Set the encoding of this content.
		 *
		 * @api setEncoding
		 * @param string $encoding Encoding to use for this content.
		 */
		public function setEncoding(string $encoding) {
			$this->encoding = $encoding;
		}

		/**
		 * Get the encoding of this content.
		 *
		 * @api getEncoding
		 * @return string
		 */
		public function getEncoding() {
			return $this->encoding;
		}

		/**
		 * Encode and return the content.
		 *
		 * @api __toString
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString() {
			$content = $this->content;

			if ($this->encoding == self::E_BASE64)
				$content = base64_encode($content);
			else if ($this->encoding == self::E_QUOTED_PRINTABLE)
				$content = quoted_printable_encode($content);

			return chunk_split($content, 70, "\r\n");
		}


		/**
		 * @var string
		 */
		private $encoding;

		/**
		 * @var string
		 */
		private $content;
	}