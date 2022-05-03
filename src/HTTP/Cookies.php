<?php
	/*
	 * Copyright (c) 2022 Kruithne (kruithne@gmail.com)
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
	 * Class HTTPContext
	 * @package KrameWork
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class Cookies
	{
		private static $defaults = [
			'expires' => 0,
			'domain' => '',
			'path' => '/',
			'secure' => true,
			'httponly' => true,
			'samesite' => 'None'
		];

		/**
		 * Retrieves a cookie by a given name.
		 * If the cookie is an array, an array is returned.
		 * If the cookie has not been sent, NULL is returned.
		 * @param string $name
		 * @return array|string|null
		 */
		public static function get(string $name) {
			return $_COOKIE[$name] ?? null;
		}

		/**
		 * Set the value of a cookie.
		 * @param string $name
		 * @param string|array $value
		 * @param array [$options]
		 */
		public static function set(string $name, string|array $value, array $options = []) {
			\setcookie($name, $value, \array_merge(self::$defaults, $options));
		}

		/**
		 * Mark a given cookie as expired.
		 * @param string $name
		 */
		public static function expire(string $name) {
			self::set($name, '', ['expires' => 1]);
		}

		/**
		 * Set the default options for cookie setting.
		 * If $merge is set to true, the provided array will merge with the existing
		 * defaults, otherwise the new array will entirely replace the defaults.
		 * @param array $options
		 * @param boolean [$merge=true]
		 */
		public static function setDefaults(array $options, $merge = true) {
			self::$defaults = $merge ? \array_merge(self::$defaults, $options) : $options;
		}

		/**
		 * Sets the value of a specific default cookie setting.
		 * @param string $key
		 * @param mixed $value
		 */
		public static function setDefault(string $key, $value) {
			self::$defaults[$key] = $value;
		}
	}