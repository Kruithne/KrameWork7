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

	namespace KrameWork\Caching;

	require_once(__DIR__.'../HTTP/HTTPContext.php');

	use KrameWork\HTTP\HTTPContext;

	class SessionInitiationException extends \Exception {}

	/**
	 * Class Session
	 * Session handling instance.
	 *
	 * @package KrameWork
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class Session implements IDataCache
	{
		/**
		 * Session constructor.
		 *
		 * @api __construct
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
		 * @api start
		 */
		public function start() {
			// Check if session is already started.
			if ($this->isActive())
				return;

			// Ensure we're not on a CLI.
			if (\PHP_SAPI == 'cli')
				throw new SessionInitiationException('Session cannot be started in CLI environment.');

			\session_start();

			if ($this->secure) {
				$remote = HTTPContext::getClientIP() ?? '';
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
		 * @api flush
		 */
		public function flush() {
			\session_unset();
			\session_regenerate_id(false);
			\session_destroy();
			\session_start();
		}

		/**
		 * Check if a session is currently active.
		 *
		 * @api isActive
		 * @return bool
		 */
		public function isActive() {
			// CLI does not support sessions.
			if (\PHP_SAPI == 'cli')
				return false;

			return \session_status() == \PHP_SESSION_ACTIVE;
		}

		/**
		 * Retrieve a session value.
		 *
		 * @api __get
		 * @param $name string Key to retrieve from the session.
		 * @return mixed
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __get(string $name) {
			return $_SESSION[$name] ?? null;
		}

		/**
		 * Set a session value.
		 *
		 * @api __set
		 * @param $name string Key to set the value with.
		 * @param $value mixed Value to store in the session.
		 * @return void
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __set(string $name, $value) {
			if($value === null)
			{
				$this->__unset($name);
				return;
			}
			$_SESSION[$name] = $value;
		}

		/**
		 * Unset a session value.
		 *
		 * @api __unset
		 * @param $name string Key to remove from the session.
		 * @return void
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __unset(string $name) {
			unset($_SESSION[$name]);
		}

		/**
		 * Store a value in the cache.
		 *
		 * @api store
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 * @param int $expire No effect for this implementation.
		 */
		public function store(string $key, $value, int $expire = 0) {
			if($value === null)
			{
				$this->__unset($key);
				return;
			}
			$_SESSION[$key] = $value;
		}

		/**
		 * Check if a value exists in the cache.
		 *
		 * @param string $key Key used to store the value.
		 * @return bool True if the key exists in the cache.
		 */
		public function exists(string $key): bool {
			return isset($_SESSION[$key]);
		}

		/**
		 * Increase a numeric value in the cache.
		 *
		 * @api increment
		 * @param string $key Key of the value.
		 * @param int $weight How much to increment the value.
		 */
		public function increment(string $key, int $weight = 1) {
			$this->store($key, ($this->$key ?? 0) + $weight);
		}

		/**
		 * Decrease a numeric value in the cache.
		 *
		 * @api decrement
		 * @param string $key Key of the value.
		 * @param int $weight How much to decrement the value.
		 */
		public function decrement(string $key, int $weight = 1) {
			$this->store($key, ($this->$key ?? 0) - $weight);
		}

		/**
		 * @var bool
		 */
		private $secure;
	}