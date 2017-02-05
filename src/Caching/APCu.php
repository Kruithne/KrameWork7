<?php
	namespace KrameWork\Caching;

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
		 *
		 * @api
		 * @param string $key Key of the value.
		 * @return mixed|null Value, or null if not found.
		 */
		public function __get(string $key) {
			$value = apcu_fetch($key, $success);
			return $success ? $value : null;
		}

		/**
		 * Store a value in the cache.
		 *
		 * @api
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 */
		public function __set(string $key, $value): void {
			apcu_store($key, $value);
		}

		/**
		 * Remove an item stored in the cache.
		 *
		 * @api
		 * @param string $key Key of the item to remove.
		 */
		public function __unset(string $key): void {
			apcu_delete($key);
		}

		/**
		 * Flush the cache, removing all stored data.
		 *
		 * @api
		 */
		public function flush(): void {
			apcu_clear_cache();
		}

		/**
		 * Increase a numeric value in the cache.
		 *
		 * @param string $key Key of the value.
		 * @param int $weight How much to increment the value.
		 */
		public function increment(string $key, int $weight): void {
			apcu_inc($key, $weight);
		}

		/**
		 * Decrease a numeric value in the cache.
		 *
		 * @param string $key Key of the value.
		 * @param int $weight How much to decrement the value.
		 */
		public function decrement(string $key, int $weight) {
			apcu_dec($key, $weight);
		}
	}