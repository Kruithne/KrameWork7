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
	require_once(__DIR__ . '/WebRequest.php');

	class InvalidMethodException extends \Exception {}

	/**
	 * Class JSONRequest
	 * Create an invoke a HTTP(s) request with a JSON response.
	 *
	 * @package KrameWork\HTTP
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class JSONRequest extends WebRequest
	{
		/**
		 * Send the request.
		 * Return boolean indicates success.
		 *
		 * @api postJson
		 * @param mixed $object The data to encode as JSON and post to the server
		 * @return bool
		 * @throws InvalidMethodException
		 */
		public function postJson(object $object):bool {
			if($this->method != WebRequest::METHOD_POST)
				throw new InvalidMethodException('JSON-body requests require METHOD_POST.');

			$url = $this->url . (strpos($this->url, '?') !== false ? '&' : '?');
			$url .= http_build_query($this->data);

			$this->sendRequest($url, json_encode($object));
			return $this->success;
		}

		/**
		 * Get the JSON decoded response from this request.
		 * Returns null if unable to decode response.
		 *
		 * @api getResponse
		 * @return mixed
		 * @throws ResponseNotAvailableException
		 */
		public function getResponse() {
			return json_decode(parent::getResponse());
		}
	}