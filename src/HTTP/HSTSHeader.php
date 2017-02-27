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
	 * Class HSTSHeader
	 * HTTP Strict Transport Security.
	 *
	 * @package KrameWork\Security
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class HSTSHeader extends HTTPHeader {
		/**
		 * HSTSHeader constructor.
		 *
		 * @api __construct
		 * @param int $maxAge How long user-agents will redirect to HTTPS in seconds.
		 * @param bool $includeSubDomains Upgrade requests for sub-domains.
		 * @param bool $preload Include on HSTSHeader preload list.
		 * @link https://hstspreload.org/
		 */
		public function __construct(int $maxAge = 63072000, bool $includeSubDomains = true, bool $preload = true) {
			$this->maxAge = $maxAge;
			$this->includeSubDomains = $includeSubDomains;
			$this->preload = $preload;
		}

		/**
		 * Set how long user-agents will redirect to HTTPS in seconds.
		 * Minimum accepted value by browsers is two months (63072000).
		 *
		 * @api setMaxAge
		 * @param int $maxAge Time period HTTPs will be supported.
		 */
		public function setMaxAge(int $maxAge) {
			$this->maxAge = $maxAge;
		}

		/**
		 * Upgrade requests for sub-domains.
		 *
		 * @api setIncludeSubDomains
		 * @param bool $include Include sub-domains.
		 */
		public function setIncludeSubDomains(bool $include) {
			$this->includeSubDomains = $include;
		}

		/**
		 * Include on HSTSHeader preload lists.
		 *
		 * @api setPreload
		 * @param bool $preload Add to preload lists.
		 */
		public function setPreload(bool $preload) {
			$this->preload = $preload;
		}

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName(): string {
			return 'Strict-Transport-Security';
		}

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue(): string {
			$value = 'max-age=' . $this->maxAge;

			if ($this->includeSubDomains)
				$value .= '; includeSubDomains';

			if ($this->preload)
				$value .= '; preload';

			return $value;
		}

		/**
		 * @var int
		 */
		protected $maxAge;

		/**
		 * @var bool
		 */
		protected $includeSubDomains;

		/**
		 * @var bool
		 */
		protected $preload;
	}