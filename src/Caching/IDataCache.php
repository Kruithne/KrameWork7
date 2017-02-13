<?php
	namespace KrameWork\Caching;

	/**
	 * Interface IDataCache
	 * Template for user-cache classes.
	 *
	 * @package KrameWork\Caching
	 * @author Kruithne (kruithne@gmail.com)
	 */
	interface IDataCache
	{
		/**
		 * Obtain a value from the cache.
		 *
		 * @api
		 * @param string $key Key of the value.
		 * @return mixed|null Value, or null if not found.
		 */
		public function __get(string $key);

		/**
		 * Store a value in the cache.
		 * Value will not expire, use store() if needed.
		 *
		 * @api
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 */
		public function __set(string $key, $value):void;

		/**
		 * Store a value in the cache.
		 *
		 * @api
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 * @param int $expire Expiry time as Unix timestamp, 0 = never.
		 */
		public function store(string $key, $value, int $expire = 0):void;

		/**
		 * Check if a value exists in the cache.
		 *
		 * @param string $key Key used to store the value.
		 * @return bool True if the key exists in the cache.
		 */
		public function exists(string $key):bool;

		/**
		 * Remove an item stored in the cache.
		 *
		 * @api
		 * @param string $key Key of the item to remove.
		 */
		public function __unset(string $key):void;

		/**
		 * Flush the cache, removing all stored data.
		 *
		 * @api
		 */
		public function flush():void;

		/**
		 * Increase a numeric value in the cache.
		 *
		 * @param string $key Key of the value.
		 * @param int $weight How much to increment the value.
		 */
		public function increment(string $key, int $weight):void;

		/**
		 * Decrease a numeric value in the cache.
		 *
		 * @param string $key Key of the value.
		 * @param int $weight How much to decrement the value.
		 */
		public function decrement(string $key, int $weight);
	}