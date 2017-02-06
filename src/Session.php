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

	class SessionInitiationException extends \Exception {}

	/**
	 * Class Session
	 * Session handling instance.
	 *
	 * @package KrameWork
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class Session
	{
		/**
		 * Session constructor.
		 *
		 * @api
		 * @param bool $autoStart Start session on instantiation.
		 * @param bool $secure Prevent session theft.
		 */
		public function __construct(bool $autoStart = true, bool $secure = true) {
			$this->secure = $secure;

			if ($autoStart)
				$this->start();
		}

		/**
		 * Attempt to start a session.
		 *
		 * @api
		 */
		public function start() {
			// Check if session is already started.
			if ($this->isActive())
				return;

			// Ensure we're not on a CLI.
			if (php_sapi_name() == 'cli')
				throw new SessionInitiationException('Session cannot be started in CLI environment.');

			session_start();

			if ($this->secure) {
				$remote = $_SERVER['REMOTE_ADDR'] ?? '';
				$client = $_SESSION['__session_client'] ?? $remote;

				if ($client != $remote) {
					$this->flush();
					$_SESSION['__session_client'] = $remote;
				}
			}
		}

		/**
		 * Delete all existing data in the session and start a
		 * newly generated one.
		 *
		 * @api
		 */
		public function flush() {
			$_SESSION = [];

			session_regenerate_id(false);
			session_destroy();
			session_start();
		}

		/**
		 * Check if a session is currently active.
		 *
		 * @api
		 * @return bool
		 */
		public function isActive() {
			// CLI does not support sessions.
			if (php_sapi_name() == 'cli')
				return false;

			return session_status() == PHP_SESSION_ACTIVE;
		}

		/**
		 * Retrieve a session value.
		 *
		 * @param $name string
		 * @return mixed
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __get($name) {
			return $_SESSION[$name] ?? null;
		}

		/**
		 * Set a session value.
		 *
		 * @param $name string
		 * @param $value mixed
		 * @return void
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __set($name, $value) {
			$_SESSION[$name] = $value;
		}

		/**
		 * Unset a session value.
		 *
		 * @param $name string
		 * @return void
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __unset($name) {
			unset($_SESSION[$name]);
		}

		/**
		 * @var bool
		 */
		private $secure;
	}