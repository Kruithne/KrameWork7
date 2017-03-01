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
	namespace KrameWork\HTTP\Request;

	use KrameWork\HTTP\HTTPHeader;

	class ResponseNotAvailableException extends \Exception {}

	/**
	 * Class WebRequest
	 * Create and invoke a HTTP(s) request.
	 *
	 * @package KrameWork\HTTP
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class WebRequest
	{
		const METHOD_POST = 'POST';
		const METHOD_GET = 'GET';

		/**
		 * WebRequest constructor.
		 *
		 * @api __construct
		 * @param string $url Request endpoint.
		 * @param string $method Request method (use class constants).
		 */
		public function __construct(string $url, string $method = self::METHOD_GET) {
			$this->url = $url;
			$this->method = $method;
			$this->data = [];
			$this->headers = ['Content-type' => 'application/x-www-form-urlencoded'];
		}

		/**
		 * Add a header to this request.
		 *
		 * @api addHeader
		 * @param string $fieldName Field name of the header.
		 * @param string $fieldValue Field value of the header.
		 */
		public function setHeader(string $fieldName, string $fieldValue) {
			$this->headers[$fieldName] = $fieldValue;
		}

		/**
		 * Add a HTTP header object to the request.
		 *
		 * @api addHeaderObject
		 * @param HTTPHeader $header Header object to add.
		 */
		public function addHeaderObject(HTTPHeader $header) {
			$this->setHeader($header->getFieldName(), $header->getFieldValue());
		}

		/**
		 * Add multiple headers to the request.
		 * Array must be in fieldName => fieldValue format.
		 *
		 * @api addHeaders
		 * @param array $headers Array of headers to add.
		 */
		public function addHeaders(array $headers) {
			foreach ($headers as $fieldName => $fieldValue)
				$this->setHeader($fieldName, $fieldValue);
		}

		/**
		 * Check if this request has a header set.
		 *
		 * @api hasHeader
		 * @param string $checkName Field name to check for.
		 * @return bool
		 */
		public function hasHeader(string $checkName):bool {
			$checkName = strtolower($checkName);
			foreach ($this->headers as $fieldName => $fieldValue)
				if (strtolower($fieldName) == $checkName)
					return true;

			return false;
		}

		/**
		 * Get a value set for this request.
		 *
		 * @param string $name Name of the value.
		 * @return mixed
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __get(string $name) {
			return $this->data[$name] ?? null;
		}

		/**
		 * run when writing data to inaccessible members.
		 *
		 * @param string $name Name of the value.
		 * @param mixed $value Value itself.
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __set(string $name, $value) {
			if (is_bool($value))
				$value = $value ? 'true' : 'false';

			$this->data[$name] = $value;
		}

		/**
		 * Send the request.
		 * Return boolean indicates success.
		 *
		 * @api send
		 * @return bool
		 */
		public function send():bool {
			$this->sendRequest($this->url, http_build_query($this->data));
			return $this->success;
		}

		/**
		 * Get the compiled headers for this request.
		 *
		 * @internal
		 * @return string
		 */
		protected function compileHeaders():string {
			$headers = '';
			foreach ($this->headers as $fieldName => $fieldValue)
				$headers .= $fieldName . ': ' . $fieldValue . "\r\n";

			return $headers;
		}

		/**
		 * Send a HTTP request.
		 *
		 * @param string $url URL endpoint to request.
		 * @param string $content Content to send with the request.
		 */
		protected function sendRequest(string $url, string $content) {
			$params = ['method' => $this->method];

			if ($this->method == self::METHOD_GET) {
				if (\strlen($content)) {
					$url .= (strpos($url, '?') !== false ? '&' : '?');
					$url .= $content;
				}
			} else if ($this->method == self::METHOD_POST) {
				$params['content'] = $content;
				$this->setHeader('Content-length', \strlen($content));
			}

			$params['header'] = $this->compileHeaders();

			$result = file_get_contents($url, false, stream_context_create(['http' => $params]));
			$this->success = $result !== false;

			if ($this->success)
				$this->result = $result;
		}

		/**
		 * Get the response from this request.
		 *
		 * @api getResponse
		 * @return string
		 * @throws ResponseNotAvailableException
		 */
		public function getResponse() {
			if ($this->result == null)
				throw new ResponseNotAvailableException('Response not available (request failed/not sent)');

			return $this->result;
		}

		/**
		 * True/false depending on success of request.
		 * Returns null if request has not yet been sent.
		 *
		 * @api success
		 * @return bool|null
		 */
		public function success() {
			return $this->success;
		}

		/**
		 * Return the response for this request.
		 *
		 * @api __toString
		 * @return string
		 * @throws ResponseNotAvailableException
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString():string {
			if ($this->result == null)
				throw new ResponseNotAvailableException('Response not available (request failed/not sent)');

			return $this->result;
		}

		/**
		 * @var null|bool
		 */
		protected $success;

		/**
		 * @var string
		 */
		protected $result;

		/**
		 * @var array
		 */
		protected $data;

		/**
		 * @var string
		 */
		protected $url;

		/**
		 * @var string
		 */
		protected $method;

		/**
		 * @var array
		 */
		protected $headers;
	}