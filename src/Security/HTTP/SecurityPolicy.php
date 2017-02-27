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
	namespace KrameWork\Security\HTTP;
	require_once(__DIR__ . '/HTTPHeader.php');

	/**
	 * Class SecurityPolicy
	 * Application security control module.
	 *
	 * @package KrameWork\Security
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class SecurityPolicy
	{
		/**
		 * SecurityPolicy constructor.
		 *
		 * @api __construct
		 * @param array|string $input Policy array (header objects) or pre-compiled string.
		 */
		public function __construct($input = []) {
			$this->compiled = [];

			if (is_array($input)) {
				$this->headers = $input;
			} else {
				$this->headers = [];

				$decode = base64_decode($input);
				if ($decode !== false) {
					$parsed = json_decode($decode);
					if ($parsed !== null)
						$this->compiled = $parsed;
				}
			}
		}

		/**
		 * Add a header to this policy.
		 *
		 * @api add
		 * @param HTTPHeader $header Header to add.
		 */
		public function add(HTTPHeader $header) {
			$this->headers[] = $header;
		}

		/**
		 * Apply this policy, compiling and setting all headers.
		 * Unless providing a pre-compiled string, call compile() first.
		 *
		 * @api apply
		 */
		public function apply() {
			foreach ($this->compiled as $header)
				header($header);
		}

		/**
		 * Compiled all headers in this policy, storing them internally.
		 * Call before applying a non pre-compiled policy.
		 *
		 * @api compile
		 */
		public function compile() {
			$this->compiled = [];
			foreach ($this->headers as $header)
				$this->compiled[] = $header->__toString();
		}

		/**
		 * Get a serialized representation of this policy.
		 * Results may be incorrect if called before compile().
		 *
		 * @api __toString
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		public function __toString():string {
			return base64_encode(json_encode($this->compiled));
		}

		/**
		 * @var HTTPHeader[]
		 */
		protected $headers;

		/**
		 * @var string[]
		 */
		protected $compiled;
	}