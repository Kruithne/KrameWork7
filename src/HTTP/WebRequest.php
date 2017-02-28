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

	class InvalidHeaderException extends \Exception {}
	class RequestNotSentException extends \Exception {}

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
			$this->headers = [];
			$this->data = [];
		}

		/**
		 * Add a header to this request.
		 *
		 * @api addHeader
		 * @param string|array $headers Header string, or array of strings.
		 * @throws InvalidHeaderException
		 */
		public function addHeader($headers) {
			if (is_array($headers)) {
				foreach ($headers as $header)
					$this->addHeader($header);

				return;
			} else if (is_string($headers)) {
				$this->headers[] = $headers;
			} else {
				throw new InvalidHeaderException('Header must be a string (or array of strings).');
			}
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
			$headers = '';
			foreach ($this->headers as $header)
				$headers .= $header . "\r\n";

			$context = stream_context_create([
				'http' => [
					'header' => $headers,
					'method' => $this->method,
					'content' => http_build_query($this->data)
				]
			]);

			$result = file_get_contents($this->url, false, $context);
			$this->success = $result !== false;

			if ($this->success)
				$this->result = $result;

			return $this->success;
		}

		/**
		 * Get the response from this request.
		 * Returns null if request has not yet been sent.
		 *
		 * @api getResponse
		 * @return string
		 * @throws RequestNotSentException
		 */
		public function getResponse():string {
			if ($this->result == null)
				throw new RequestNotSentException();

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
		 * If the request is not available, an empty string is returned.
		 *
		 * @api __toString
		 * @return string
		 * @throws RequestNotSentException
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString():string {
			if ($this->result == null)
				throw new RequestNotSentException();

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