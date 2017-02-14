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

	require_once(__DIR__ . "/IDataCache.php");

	/**
	 * Class APCu
	 * Interface for APCu.
	 *
	 * @package KrameWork\Caching
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class APCu implements IDataCache
	{
		/**
		 * Obtain a value from the cache.
		 * Returns null if the item does not exist.
		 *
		 * @api __get
		 * @param string $key Key of the value.
		 * @return mixed|null
		 */
		public function __get(string $key) {
			$value = apcu_fetch($key, $success);
			return $success ? $value : null;
		}

		/**
		 * Store a value in the cache.
		 *
		 * @api store
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 * @param int $expire 60*60*24*30 >= Unix Timestamp, otherwise seconds. 0 = Never.
		 */
		public function store(string $key, $value, int $expire = 0) {
			if ($expire > 60*60*24*30)
				$expire = time() - $expire;

			apcu_store($key, $value, $expire);
		}

		/**
		 * Check if a value exists in the cache.
		 *
		 * @param string $key Key used to store the value.
		 * @return bool True if the key exists in the cache.
		 */
		public function exists(string $key): bool {
			return apcu_exists($key);
		}

		/**
		 * Store a value in the cache.
		 * Value will not expire, use store() if needed.
		 *
		 * @api __set
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 */
		public function __set(string $key, $value){
			$this->store($key, $value);
		}

		/**
		 * Remove an item stored in the cache.
		 *
		 * @api __unset
		 * @param string $key Key of the item to remove.
		 */
		public function __unset(string $key) {
			apcu_delete($key);
		}

		/**
		 * Flush the cache, removing all stored data.
		 *
		 * @api flush
		 */
		public function flush(): void {
			apcu_clear_cache();
		}

		/**
		 * Increase a numeric value in the cache.
		 *
		 * @api increment
		 * @param string $key Key of the value.
		 * @param int $weight How much to increment the value.
		 */
		public function increment(string $key, int $weight) {
			apcu_inc($key, $weight);
		}

		/**
		 * Decrease a numeric value in the cache.
		 *
		 * @api decrement
		 * @param string $key Key of the value.
		 * @param int $weight How much to decrement the value.
		 */
		public function decrement(string $key, int $weight) {
			apcu_dec($key, $weight);
		}
	}