<?php
	namespace KrameWork\API;

	use KrameWork\HTTP\Request\JSONRequest;
	use KrameWork\HTTP\Request\WebRequest;

	class reCAPTCHAException extends \Exception {}

	class reCAPTCHA
	{
		public function __construct(string $secret) {
			$this->secret = $secret;
		}

		public function setRemoteIP(string $ip) {
			$this->ip = $ip;
		}

		public function validate(string $token):bool {
			$ip = $this->ip ?? $_SERVER['REMOTE_ADDR'];
			if ($ip == null || \strlen($ip) == 0)
				throw new reCAPTCHAException('Remote IP is invalid or missing.');

			$req = new JSONRequest('https://www.google.com/recaptcha/api/siteverify', WebRequest::METHOD_POST);
			// ToDo: Implement.
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