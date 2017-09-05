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
		 * Obtain the connecting clients IP, if available.
		 * Note that this can include the remote port, as some proxies add it to X-Forwarded-For.
		 *
		 * @api getClientIP
		 * @return string|null
		 */
		public static function getClientIP() {
			if (!isset($_SERVER))
				return null;

			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];

			else if (isset($_SERVER['REMOTE_ADDR']))
				return $_SERVER['REMOTE_ADDR'];

			return null;
		}

		/**
		 * Obtain an array containing all files with the given key.
		 *
		 * @api getFiles
		 * @param string $key Key to lookup files for.
		 * @param bool $useWrappers Use KrameWork file wrappers.
		 * @return \ArrayObject[]|Storage\UploadedFile[]
		 */
		public static function getFiles(string $key, bool $useWrappers = true) {
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
		public static function hasFile(string $key) {
			return array_key_exists($key, $_FILES);
		}

		/**
		 * Retrieve the decoded query string for this request.
		 *
		 * @api getQueryData
		 * @return array
		 */
		public static function getQueryData() {
			if (self::$cacheQueryData)
				return self::$cacheQueryData;

			self::$cacheQueryData = self::extractURLEncodedData(self::getQueryString());
			return self::$cacheQueryData;
		}

		/**
		 * Retrieve a value from the query string with the given key.
		 *
		 * @api getQueryDataValue
		 * @param string $key Key contained in the query string.
		 * @return mixed|null
		 */
		public static function getQueryDataValue(string $key) {
			return self::getQueryData()[$key] ?? null;
		}

		/**
		 * Retrieve multiple values from the query string.
		 * Accepts a variable amount of string arguments.
		 *
		 * @api getQueryDataValues
		 * @return array
		 */
		public static function getQueryDataValues():array {
			$out = [];
			foreach (func_get_args() as $arg)
				$out[] = self::getQueryDataValue($arg);

			return $out;
		}

		/**
		 * Check if this request contains form data.
		 * Occurs when content-type is multipart/form-data or application/x-www-form-urlencoded
		 *
		 * @api hasFormData
		 * @return bool
		 */
		public static function hasFormData():bool {
			if (self::$hasFormData === null)
				self::checkFormDataPresence();

			return self::$hasFormData;
		}

		/**
		 * Check if this request contains multipart form data.
		 * Occurs when content-type is multipart/form-data
		 *
		 * @api hasMultipartFormData
		 * @return bool
		 */
		public static function hasMultipartFormData():bool {
			if (self::$hasMultipartFormData === null)
				self::checkFormDataPresence();

			return self::$hasMultipartFormData;
		}

		/**
		 * Retrieve a value from submitted form data with the given key.
		 *
		 * @api getFormDataValue
		 * @param string $key Key to get value for.
		 * @return mixed|null
		 */
		public static function getFormDataValue(string $key) {
			return self::getFormData()[$key] ?? null;
		}

		/**
		 * Retrieve multiple values from submitted form data.
		 * Accepts variable amount of string arguments.
		 *
		 * @api getFormDataValues
		 * @return array
		 */
		public static function getFormDataValues():array {
			$out = [];
			foreach (func_get_args() as $arg)
				$out[] = self::getFormDataValue($arg);

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
		public static function getFormData():array {
			if (self::$cacheFormData)
				return self::$cacheFormData;

			if (!self::hasFormData())
				return [];

			// If the content-type is multipart/form-data, then extracting the raw input manually is
			// not available, thus we resort to using $_POST, which luckily is more likely to contain
			// correct data in this scenario. During application/x-www-form-urlencoded requests, we
			// parse the raw data manually to avoid issues with $_POST being blank given valid yet
			// reserved characters within keys, or under certain server configurations.
			self::$cacheFormData = self::hasMultipartFormData() ? $_POST : self::extractURLEncodedData(self::getRequestContent());
			return self::$cacheFormData;
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
		public static function getJSON(bool $decode = true, bool $ignoreContentType = false, bool $wrapper = true) {
			if (!$ignoreContentType && self::getContentType(false) != 'application/json')
				throw new InvalidRequestTypeException('Request content is not declared as application/json.');

			if ($wrapper) {
				require_once(__DIR__ . '/../Storage/JSONFile.php');
				try {
					$json = new JSONFile(null, true, false);
					$json->setRawData(self::getRequestContent());
					$json->read();
					return $json;
				} catch (JSONException $e) {
					throw new InvalidRequestTypeException('Request content did not contain valid json.');
				}
			} else {
				$content = self::getRequestContent();
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
		public static function isSecure():bool {
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
		public static function getRequestContent():string {
			return file_get_contents('php://input');
		}

		/**
		 * Retrieve the length of the raw request content.
		 *
		 * @api getContentLength
		 * @return int
		 */
		public static function getContentLength():int {
			return intval($_SERVER['CONTENT_LENGTH'] ?? 0);
		}

		/**
		 * Retrieve the user-agent string for the current request.
		 * Returns 'Unknown' if not available.
		 *
		 * @api getUserAgent
		 * @return string
		 */
		public static function getUserAgent():string {
			return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
		}

		/**
		 * Retrieve the referer URL for this request.
		 * Returns an empty string if not available.
		 *
		 * @api getReferer
		 * @return string
		 */
		public static function getReferrer():string {
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
		public static function getContentType(bool $parameters = true):string {
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
		public static function getRemoteAddress():string {
			return $_SERVER['REMOTE_ADDR'] ?? '';
		}

		/**
		 * Retrieve the URI of this request (includes query string).
		 * Returns an empty string if not available.
		 *
		 * @api getRequestURI
		 * @return string
		 */
		public static function getRequestURI():string {
			return $_SERVER['REQUEST_URI'] ?? '';
		}

		/**
		 * Retrieve the query string used in this request.
		 * Returns an empty string if not available.
		 *
		 * @api getQueryString
		 * @return string
		 */
		public static function getQueryString():string {
			return $_SERVER['QUERY_STRING'] ?? '';
		}

		/**
		 * Retrieve the method of this request.
		 * Defaults to 'GET' if not available.
		 *
		 * @api getRequestMethod
		 * @return string
		 */
		public static function getRequestMethod():string {
			return $_SERVER['REQUEST_METHOD'] ?? 'GET';
		}

		/**
		 * Check the presence of form data in the request.
		 *
		 * @internal
		 */
		private static function checkFormDataPresence() {
			$contentType = self::getContentType(false);
			self::$hasMultipartFormData = $contentType == 'multipart/form-data';
			self::$hasFormData = self::$hasMultipartFormData || $contentType == 'application/x-www-form-urlencoded';
		}

		/**
		 * Decode a URL encoded key/value string.
		 *
		 * @internal
		 * @param string $input URL encoded string to decode.
		 * @return array Decoded key/value pair array.
		 */
		private static function extractURLEncodedData(string $input):array {
			\mb_parse_str($input, $out);
			return $out;
		}

		/**
		 * True if the request has form data.
		 * @var bool
		 */
		private static $hasFormData;

		/**
		 * True if the form data is multipart.
		 * @var bool
		 */
		private static $hasMultipartFormData;

		/**
		 * Internal cache for parsed form data.
		 * @var array
		 */
		private static $cacheFormData;

		/**
		 * Internal cache for parsed query data.
		 * @var array
		 */
		private static $cacheQueryData;
	}