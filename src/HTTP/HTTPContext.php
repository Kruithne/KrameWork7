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

	use KrameWork\Storage\JSONException;
	use KrameWork\Storage\JSONFile;
	use KrameWork\Storage\UploadedFile;

	class InvalidRequestTypeException extends \Exception {}

	/**
	 * Class HTTPContext
	 * @package KrameWork
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class HTTPContext
	{
		/**
		 * Obtain an array containing all files with the given key.
		 *
		 * @api getFiles
		 * @param string $key Key to lookup files for.
		 * @param bool $useWrappers Use KrameWork file wrappers.
		 * @return \ArrayObject[]|Storage\UploadedFile[]
		 */
		public function getFiles(string $key, bool $useWrappers = true) {
			$files = [];
			$node = $_FILES[$key] ?? null;

			// No files to return, lame.
			if ($node === null)
				return $files;

			$size = count($node['name']);
			if ($size == 0) // Bork.
				return $files;

			if ($useWrappers)
				require_once(__DIR__ . '/../Storage/UploadedFile.php');

			for ($i = 0; $i < $size; $i++) {
				if ($useWrappers) {
					$files[] = new UploadedFile($node['tmp_name'][$i], $node['name'][$i], $node['error'][$i]);
				} else {
					$files[] = new \ArrayObject([
						'name' => $node['name'][$i],
						'type' => $node['type'][$i],
						'path' => $node['tmp_name'][$i],
						'error' => $node['error'][$i],
						'size' => $node['size'][$i]
					], \ArrayObject::ARRAY_AS_PROPS);
				}
			}

			return $files;
		}

		/**
		 * Check if this request contains an uploaded file with the given key.
		 *
		 * @api hasFile
		 * @param string $key Key to check for.
		 * @return bool
		 */
		public function hasFile(string $key) {
			return array_key_exists($key, $_FILES);
		}

		/**
		 * Retrieve the decoded query string for this request.
		 *
		 * @api getQueryData
		 * @return array
		 */
		public function getQueryData() {
			if ($this->cacheQueryData)
				return $this->cacheQueryData;

			$this->cacheQueryData = $this->extractURLEncodedData($this->getQueryString());
			return $this->cacheQueryData;
		}

		/**
		 * Retrieve a value from the query string with the given key.
		 *
		 * @api getQueryDataValue
		 * @param string $key Key contained in the query string.
		 * @return mixed|null
		 */
		public function getQueryDataValue(string $key) {
			return $this->getQueryData()[$key] ?? null;
		}

		/**
		 * Retrieve multiple values from the query string.
		 * Accepts a variable amount of string arguments.
		 *
		 * @api getQueryDataValues
		 * @return array
		 */
		public function getQueryDataValues():array {
			$out = [];
			foreach (func_get_args() as $arg)
				$out[] = $this->getQueryDataValue($arg);

			return $out;
		}

		/**
		 * Check if this request contains form data.
		 * Occurs when content-type is multipart/form-data or application/x-www-form-urlencoded
		 *
		 * @api hasFormData
		 * @return bool
		 */
		public function hasFormData():bool {
			if ($this->hasFormData === null)
				$this->checkFormDataPresence();

			return $this->hasFormData;
		}

		/**
		 * Check if this request contains multipart form data.
		 * Occurs when content-type is multipart/form-data
		 *
		 * @api hasMultipartFormData
		 * @return bool
		 */
		public function hasMultipartFormData():bool {
			if ($this->hasMultipartFormData === null)
				$this->checkFormDataPresence();

			return $this->hasMultipartFormData;
		}

		/**
		 * Retrieve a value from submitted form data with the given key.
		 *
		 * @api getFormDataValue
		 * @param string $key Key to get value for.
		 * @return mixed|null
		 */
		public function getFormDataValue(string $key) {
			return $this->getFormData()[$key] ?? null;
		}

		/**
		 * Retrieve multiple values from submitted form data.
		 * Accepts variable amount of string arguments.
		 *
		 * @api getFormDataValues
		 * @return array
		 */
		public function getFormDataValues():array {
			$out = [];
			foreach (func_get_args() as $arg)
				$out[] = $this->getFormDataValue($arg);

			return $out;
		}

		/**
		 * Retrieve the content of the request as decoded form data.
		 * Result is stored in a key/value pair array.
		 * Array values (foo[]) are returned as arrays.
		 *
		 * @api getFormData
		 * @return array
		 */
		public function getFormData():array {
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
		 * Retrieve the request content as (optionally decoded) JSON.
		 * Skipping decode and content-type validation: use getRequestContent() instead.
		 *
		 * @api getJSON
		 * @param bool $decode Parse the string and validate it.
		 * @param bool $ignoreContentType Skip validation of the content-type.
		 * @param bool $wrapper Wrap the decoded JSON object in a JSONFile wrapper.
		 * @return mixed|string
		 * @throws InvalidRequestTypeException
		 */
		public function getJSON(bool $decode = true, bool $ignoreContentType = false, bool $wrapper = true) {
			if (!$ignoreContentType && $this->getContentType(false) != 'application/json')
				throw new InvalidRequestTypeException('Request content is not declared as application/json.');

			if ($wrapper) {
				require_once(__DIR__ . '/../Storage/JSONFile.php');
				try {
					return new JSONFile('php://input', true, true);
				} catch (JSONException $e) {
					throw new InvalidRequestTypeException('Request content did not contain valid json.');
				}
			} else {
				$content = $this->getRequestContent();
				if ($decode) {
					$decoded = json_decode($content);
					if ($decoded === false)
						throw new InvalidRequestTypeException('Request content did not contain valid json.');

					return $decoded;
				}

				return $content;
			}
		}

		/**
		 * Check if this request was made over https protocol.
		 *
		 * @api isSecure
		 * @return bool
		 */
		public function isSecure():bool {
			// ISAPI IIS returns "off", others do not set.
			return ($_SERVER['HTTPS'] ?? 'off') !== 'off';
		}

		/**
		 * Retrieve the raw content of the request.
		 * Result is not parsed or validated.
		 *
		 * @api getRequestContent
		 * @return string
		 */
		public function getRequestContent():string {
			return file_get_contents('php://input');
		}

		/**
		 * Retrieve the length of the raw request content.
		 *
		 * @api getContentLength
		 * @return int
		 */
		public function getContentLength():int {
			return intval($_SERVER['CONTENT_LENGTH'] ?? 0);
		}

		/**
		 * Retrieve the user-agent string for the current request.
		 * Returns 'Unknown' if not available.
		 *
		 * @api getUserAgent
		 * @return string
		 */
		public function getUserAgent():string {
			return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
		}

		/**
		 * Retrieve the referer URL for this request.
		 * Returns an empty string if not available.
		 *
		 * @api getReferer
		 * @return string
		 */
		public function getReferrer():string {
			return $_SERVER['HTTP_REFERER'] ?? '';
		}

		/**
		 * Retrieve the content-type of this request.
		 * Returns 'text/plain' if not available.
		 *
		 * @api getContentType
		 * @param bool $parameters Include content-type parameters.
		 * @return string
		 */
		public function getContentType(bool $parameters = true):string {
			$type = $_SERVER['CONTENT_TYPE'] ?? 'text/plain';
			if (!$parameters) {
				$parting = strpos($type, ';');
				if ($parting !== false)
					$type = substr($type, 0, $parting);
			}
			return $type;
		}

		/**
		 * Retrieve the remote address for this request.
		 * Returns an empty string if not available.
		 *
		 * @api getRemoteAddress
		 * @return string
		 */
		public function getRemoteAddress():string {
			return $_SERVER['REMOTE_ADDR'] ?? '';
		}

		/**
		 * Retrieve the URI of this request (includes query string).
		 * Returns an empty string if not available.
		 *
		 * @api getRequestURI
		 * @return string
		 */
		public function getRequestURI():string {
			return $_SERVER['REQUEST_URI'] ?? '';
		}

		/**
		 * Retrieve the query string used in this request.
		 * Returns an empty string if not available.
		 *
		 * @api getQueryString
		 * @return string
		 */
		public function getQueryString():string {
			return $_SERVER['QUERY_STRING'] ?? '';
		}

		/**
		 * Retrieve the method of this request.
		 * Defaults to 'GET' if not available.
		 *
		 * @api getRequestMethod
		 * @return string
		 */
		public function getRequestMethod():string {
			return $_SERVER['REQUEST_METHOD'] ?? 'GET';
		}

		/**
		 * Check the presence of form data in the request.
		 *
		 * @internal
		 */
		private function checkFormDataPresence() {
			$contentType = $this->getContentType(false);
			$this->hasMultipartFormData = $contentType == 'multipart/form-data';
			$this->hasFormData = $this->hasMultipartFormData || $contentType == 'application/x-www-form-urlencoded';
		}

		/**
		 * Decode a URL encoded key/value string.
		 *
		 * @internal
		 * @param string $input URL encoded string to decode.
		 * @return array Decoded key/value pair array.
		 */
		private function extractURLEncodedData(string $input):array {
			$out = [];
			$components = explode('&', $input);
			foreach ($components as $component) {
				$parts = explode('=', $component);
				if (count($parts) < 2)
					continue;

				list($key, $value) = $parts;
				$value = urldecode($value);

				// Skip zero-length values.
				if (\strlen($value) == 0)
					continue;

				$keyLen = \strlen($key);
				if (substr($key, $keyLen - 2) == '[]') {
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
		 * @var array
		 */
		private $cacheQueryData;
	}