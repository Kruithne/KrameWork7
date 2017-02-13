<?php
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
		 *
		 * @api __get
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
		 * @api store
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 * @param int $expire 60*60*24*30 >= Unix Timestamp, otherwise seconds. 0 = Never.
		 */
		public function store(string $key, $value, int $expire = 0): void {
			if ($expire > 60*60*24*30)
				$expire = time() - $expire;

			apcu_store($key, $value, $expire);
		}

		/**
		 * Store a value in the cache.
		 * Value will not expire, use store() if needed.
		 *
		 * @api __set
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 */
		public function __set(string $key, $value): void {
			$this->store($key, $value);
		}

		/**
		 * Remove an item stored in the cache.
		 *
		 * @api __unset
		 * @param string $key Key of the item to remove.
		 */
		public function __unset(string $key): void {
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
		public function increment(string $key, int $weight): void {
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