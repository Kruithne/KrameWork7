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

	namespace KrameWork;

	class InvalidRequestTypeException extends \Exception {}

	/**
	 * Class HTTPContext
	 * @package KrameWork
	 */
	class HTTPContext
	{
		/**
		 * Retrieve the decoded query string for this request.
		 * @return array
		 */
		public function getQueryData() {
			if ($this->cacheQueryData)
				return $this->cacheQueryData;

			$this->cacheQueryData = $this->extractURLEncodedData($this->getQueryString());
			return $this->cacheQueryData;
		}

		/**
		 * Retrieve a query data value.
		 * @param string $key
		 * @return mixed|null
		 */
		public function getQueryDataValue(string $key) {
			return $this->getQueryData()[$key] ?? null;
		}

		/**
		 * Retrieve a variable amount of query data values.
		 * @return array
		 */
		public function getQueryDataValues() {
			$out = [];
			foreach (func_get_args() as $arg)
				$out[] = $this->getQueryDataValue($arg);

			return $out;
		}

		/**
		 * Check if this request contains form data.
		 * @return bool
		 */
		public function hasFormData() {
			if ($this->hasFormData === null)
				$this->checkFormDataPresence();

			return $this->hasFormData;
		}

		/**
		 * Check if this request contains multipart form data.
		 * @return bool|null
		 */
		public function hasMultipartFormData() {
			if ($this->hasMultipartFormData === null)
				$this->checkFormDataPresence();

			return $this->hasMultipartFormData;
		}

		/**
		 * Retrieve a form data value.
		 * @param string $key
		 * @return mixed|null
		 */
		public function getFormDataValue(string $key) {
			return $this->getFormData()[$key] ?? null;
		}

		/**
		 * Retrieve a variable amount of form data values.
		 * @return array
		 */
		public function getFormDataValues() {
			$out = [];
			foreach (func_get_args() as $arg)
				$out[] = $this->getFormDataValue($arg);

			return $out;
		}

		/**
		 * Retrieve the content of the request as decoded form data.
		 * @return array
		 */
		public function getFormData() {
			if ($this->cacheFormData)
				return $this->cacheFormData;

			if (!$this->hasFormData())
				return [];

			// If the content-type is multipart/form-data, then extracting the raw input manually is
			// not available, thus we resort to using $_POST, which luckily is more likely to contain
			// correct data in this scenario. During application/x-www-form-urlencoded requests, we
			// parse the raw data manually to avoid issues with $_POST being blank given valid yet
			// reserved characters within keys, or under certain server configurations.
			$this->cacheFormData = $this->hasMultipartFormData() ? $_POST : $this->extractURLEncodedData($this->getRequestContent());
			return $this->cacheFormData;
		}

		/**
		 * Retrieve the content of the request as JSON.
		 * @param bool $decode If true, will parse the string and validate it.
		 * @param bool $ignoreContentType If true, no validation of the content type will occur.
		 * @return mixed|string
		 * @throws InvalidRequestTypeException
		 */
		public function getJSON($decode = true, $ignoreContentType = false) {
			if (!$ignoreContentType && $this->getContentType(false) != "application/json")
				throw new InvalidRequestTypeException("Request content is not declared as application/json.");

			$content = $this->getRequestContent();
			if ($decode) {
				$decoded = json_decode($content);
				if ($decoded === false)
					throw new InvalidRequestTypeException("Request content did not contain valid json.");

				return $decoded;
			}

			return $content;
		}

		/**
		 * Retrieve the raw content of the request.
		 * @return string
		 */
		public function getRequestContent():string {
			return file_get_contents("php://input");
		}

		/**
		 * Retrieve the length of the raw request content.
		 * @return int
		 */
		public function getContentLength():int {
			return intval($_SERVER["CONTENT_LENGTH"] ?? 0);
		}

		/**
		 * Retrieve the user-agent string for the current request.
		 * Defaults to 'Unknown' if unavailable.
		 * @return string
		 */
		public function getUserAgent():string {
			return $_SERVER["HTTP_USER_AGENT"] ?? "Unknown";
		}

		/**
		 * Retrieve the referer URL for this request.
		 * Defaults to a blank string if not provided by the server.
		 * @return string
		 */
		public function getReferer():string {
			return $_SERVER["HTTP_REFERER"] ?? "";
		}

		/**
		 * Retrieve the content-type of this request.
		 * Defaults to 'text/plain' if unable to establish.
		 * @param bool $parameters If true, will include content-type parameters.
		 * @return string
		 */
		public function getContentType(bool $parameters = true):string {
			$type = $_SERVER["CONTENT_TYPE"] ?? "text/plain";
			if (!$parameters) {
				$parting = strpos($type, ";");
				if ($parting !== false)
					$type = substr($type, 0, $parting);
			}
			return $type;
		}

		/**
		 * Retrieve the remote address for this request.
		 * Defaults to null if unable to establish.
		 * @return string
		 */
		public function getRemoteAddress():string {
			return $_SERVER["REMOTE_ADDR"] ?? null;
		}

		/**
		 * Retrieve the URI of this request (includes query string).
		 * Defaults to null if unable to establish.
		 * @return string
		 */
		public function getRequestURI():string {
			return $_SERVER["REQUEST_URI"] ?? null;
		}

		/**
		 * Retrieve the query string used in this request.
		 * Defaults to a blank string if not provided.
		 * @return string
		 */
		public function getQueryString():string {
			return $_SERVER["QUERY_STRING"] ?? "";
		}

		/**
		 * Retrieve the method of this request.
		 * Defaults to 'GET' if unable to establish.
		 * @return string
		 */
		public function getRequestMethod():string {
			return $_SERVER["REQUEST_METHOD"] ?? "GET";
		}

		/**
		 * Internal function to check and cache the presence of form data.
		 */
		private function checkFormDataPresence() {
			$contentType = $this->getContentType(false);
			$this->hasMultipartFormData = $contentType == "multipart/form-data";
			$this->hasFormData = $this->hasMultipartFormData || $contentType == "application/x-www-form-urlencoded";
		}

		/**
		 * Decode a URL encoded key/value string.
		 * @param string $input URL encoded key/value string.
		 * @return array
		 */
		private function extractURLEncodedData(string $input) {
			$out = [];
			$components = explode("&", $input);
			foreach ($components as $component) {
				list($key, $value) = explode("=", $component);
				$value = urldecode($value);

				// Skip zero-length values.
				if (strlen($value) == 0)
					continue;

				$keyLen = strlen($key);
				if (substr($key, $keyLen - 2) == "[]") {
					$key = urldecode(substr($key, 0, $keyLen - 2));
					if (!array_key_exists($key, $out))
						$out[$key] = [];

					$out[$key][] = $value;
				} else {
					$out[urldecode($key)] = $value;
				}
			}
			return $out;
		}

		/**
		 * True if the request has form data.
		 * @var bool
		 */
		private $hasFormData;

		/**
		 * True if the form data is multipart.
		 * @var bool
		 */
		private $hasMultipartFormData;

		/**
		 * Internal cache for parsed form data.
		 * @var array
		 */
		private $cacheFormData;

		/**
		 * Internal cache for parsed query data.
		 * @var
		 */
		private $cacheQueryData;
	}