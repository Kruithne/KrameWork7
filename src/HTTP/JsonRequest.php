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

	/**
	 * Class WebRequest
	 * Create and invoke a JSON based API over an HTTP(s) request.
	 *
	 * @package KrameWork\HTTP
	 * @author docpify <morten@rusnafe.no>
	 */
	class JsonRequest extends WebRequest
	{
		/**
		 * WebRequest constructor.
		 *
		 * @api __construct
		 * @param string $url Request endpoint.
		 * @param string $method Request method (use class constants).
		 */
		public function __construct(string $url, string $method = self::METHOD_GET) {
			parent::__construct($url, $method);
		}

		/**
		 * Send the request.
		 * Return boolean indicates success.
		 *
		 * @api postJson
		 * @param mixed $object The data to encode as JSON and post to the server
		 * @return bool
		 * @throws InvalidRequestTypeException
		 */
		public function postJson(object $object):bool {
			if($this->method != WebRequest::METHOD_POST)
				throw new InvalidRequestTypeException("postJson is only supported for post requests. Do you want send()?");

			$headers = '';
			foreach ($this->headers as $header)
				$headers .= $header . "\r\n";

			$url = $this->url . (strpos($this->url, '?') !== false ? '&' : '?');
			$url .= http_build_query($this->data);

			$context = stream_context_create([
				'http' => [
					'header' => $headers,
					'method' => $this->method,
					'content' => json_encode($object)
				]
			]);

			$result = file_get_contents($url, false, $context);
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
		 * @return \StdClass
		 */
		public function getResponse(): \StdClass {
			return json_decode($this->result);
		}
	}