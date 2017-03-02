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
	 * Class InvalidOriginException
	 * Exception thrown if an invalid origin syntax is supplied.
	 *
	 * @package KrameWork\HTTP
	 * @author docpify <morten@runsafe.no>
	 */
	class InvalidOriginException extends \Exception
	{
		/**
		 * InvalidOriginException constructor.
		 */
		public function __construct() {
			parent::__construct('Origin must either be * or http://fqdn or https://fqdn');
		}
	}

	/**
	 * Class AllowOriginHeader
	 * Header controlling what origins are allowed to perform cross-origin requests.
	 *
	 * @package KrameWork\HTTP
	 * @author docpify <morten@runsafe.no>
	 */
	class AllowOriginHeader extends HTTPHeader {
		/**
		 * AllowOriginHeader constructor.
		 *
		 * @api __construct
		 * @param string $origin
		 * @throws InvalidOriginException
		 */
		public function __construct(string $origin = '*') {

			if($origin != '*' && !preg_match('/^https?://[^\/]*$/', $origin))
				throw new InvalidOriginException();

			$this->origin = $origin;
		}

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName(): string {
			return 'Access-Control-Allow-Origin';
		}

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue(): string {
			return $this->origin;
		}

		/**
		 * @var string
		 */
		private $origin;
	}

	/**
	 * Class AllowMethodsHeader
	 * Header controlling what methods are allowed to be used for cross-origin requests.
	 *
	 * @package KrameWork\HTTP
	 * @author docpify <morten@runsafe.no>
	 */
	class AllowMethodsHeader extends HTTPHeader {
		/**
		 * AllowMethodsHeader constructor.
		 *
		 * @api __construct
		 * @param array $methods
		 */
		public function __construct($methods = ['GET','POST','OPTIONS']) {
			$this->methods = $methods;
		}

		/**
		 * Add a method to allow
		 *
		 * @api allow
		 * @param string $method
		 * @return AllowMethodsHeader
		 */
		public function allow(string $method): AllowMethodsHeader {
			$this->methods[] = $method;
			return $this;
		}

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName(): string {
			return 'Access-Control-Allow-Methods';
		}

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue(): string {
			return join(',', $this->methods);
		}

		/**
		 * @var string[]
		 */
		private $methods;
	}

	/**
	 * Class AllowHeadersHeader
	 * Header controlling what headers are allowed to be used in cross-origin requests.
	 *
	 * @package KrameWork\HTTP
	 * @author docpify <morten@runsafe.no>
	 */
	class AllowHeadersHeader extends HTTPHeader
	{
		/**
		 * AllowHeadersHeader constructor.
		 *
		 * @api __construct
		 * @param null $headers
		 */
		public function __construct($headers = null) {
			if($headers == null)
				$this->headers = ['Content-Type','Cookie'];
			else
				$this->headers = $headers;
		}

		/**
		 * Add a header to allow
		 *
		 * @api add
		 * @param $header string
		 * @return AllowHeadersHeader
		 */
		public function add(string $header): AllowHeadersHeader {
			$this->headers[] = $header;
			return $this;
		}

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName(): string {
			return 'Access-Control-Allow-Headers';
		}

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue(): string {
			return join(', ', $this->headers);
		}

		/**
		 * @var string[]
		 */
		private $headers;
	}

	/**
	 * Class AllowCredentialsHeader
	 * Header controlling if credentials are allowed for cross-origin requests.
	 *
	 * @package KrameWork\HTTP
	 * @author docpify <morten@runsafe.no>
	 */
	class AllowCredentialsHeader extends HTTPHeader
	{
		/**
		 * AllowCredentialsHeader constructor.
		 *
		 * @api __construct
		 * @param bool $allow
		 */
		public function __construct(bool $allow = false) {
			$this->allowed = $allow;
		}

		/**
		 * Allow credentials in cross-origin requests.
		 *
		 * @api allow
		 * @return AllowCredentialsHeader
		 */
		public function allow() {
			$this->allowed = true;
			return $this;
		}

		/**
		 * Disallow credentials in cross-origin requests.
		 *
		 * @api deny
		 * @return AllowCredentialsHeader
		 */
		public function deny() {
			$this->allowed = false;
			return $this;
		}

		/**
		 * Get the field name for this header.
		 *
		 * @api getFieldName
		 * @return string
		 */
		public function getFieldName(): string {
			return 'Access-Control-Allow-Credentials';
		}

		/**
		 * Get the field value for this header.
		 *
		 * @api getFieldValue
		 * @return string
		 */
		public function getFieldValue(): string {
			return $this->allowed ? 'true' : 'false';
		}

		/**
		 * @var bool
		 */
		private $allowed;
	}

	/**
	 * Class CORSHeaders
	 * Cross-Origin Resource Sharing.
	 *
	 * @package KrameWork\HTTP
	 * @author docpify <morten@runsafe.no>
	 */
	class CORSHeaders {
		/**
		 * CORSHeaders constructor.
		 *
		 * @api __construct
		 * @param string $origin
		 * @param array $methods
		 * @param array $headers
		 * @param bool $credential
		 */
		public function __construct(string $origin = '*', array $methods = ['GET','POST','OPTIONS'], array $headers = ['Content-Type','Cookie'], bool $credential = false) {
			$this->headers = [
				new AllowOriginHeader($origin),
				new AllowMethodsHeader($methods),
				new AllowHeadersHeader($headers),
				new AllowCredentialsHeader($credential)
			];
		}

		/**
		 * Add the CORS headers to a HTTPHeaders instance
		 *
		 * @param HTTPHeaders $headers
		 */
		public function apply(HTTPHeaders $headers) {
			foreach ($this->headers as $header)
				$headers->add($header);
		}

		/**
		 * @var array
		 */
		private $headers;
	}