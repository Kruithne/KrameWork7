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
	namespace KrameWork\API;

	use KrameWork\HTTP\Request\JSONRequest;
	use KrameWork\HTTP\Request\WebRequest;

	require_once(__DIR__ . '/../HTTP/Request/JSONRequest.php');

	class reCAPTCHAException extends \Exception {}

	/**
	 * Class reCAPTCHA
	 * API module for third-party service: reCAPTCHA
	 *
	 * @package KrameWork\API
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class reCAPTCHA
	{
		/**
		 * reCAPTCHA constructor.
		 *
		 * @api __construct
		 * @param string $secret Secret API key.
		 */
		public function __construct(string $secret) {
			$this->secret = $secret;
		}

		/**
		 * Manually set the remote IP to use.
		 *
		 * @api setRemoteIP
		 * @param string $ip IP to use.
		 */
		public function setRemoteIP(string $ip) {
			$this->ip = $ip;
		}

		/**
		 * Attempt to validate a captcha response.
		 *
		 * @api validate
		 * @param string $token Token from the client.
		 * @return bool
		 * @throws reCAPTCHAException
		 */
		public function validate(string $token):bool {
			$ip = $this->ip ?? $_SERVER['REMOTE_ADDR'];
			if ($ip == null || \strlen($ip) == 0)
				throw new reCAPTCHAException('Remote IP is invalid or missing.');

			$req = new JSONRequest('https://www.google.com/recaptcha/api/siteverify', WebRequest::METHOD_POST);
			$req->secret = $this->secret;
			$req->response = $token;
			$req->remoteip = $ip;

			if (!$req->send())
				throw new reCAPTCHAException('Could not contact reCAPTCHA server.');

			$resp = $req->getResponse();
			if ($resp === null)
				throw new reCAPTCHAException('Invalid/unexpected response from reCAPTCHA server.');

			return $resp->success;
		}

		/**
		 * @var string
		 */
		private $secret;

		/**
		 * @var string
		 */
		private $ip;
	}