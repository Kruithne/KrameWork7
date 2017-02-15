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

	require_once(__DIR__ . '/IDataCache.php');

	class InvalidFileException extends \Exception {}

	/**
	 * Class FileCache
	 * Proof-of-concept cache implementation via hard file.
	 *
	 * @package KrameWork\Caching
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class FileCache implements IDataCache
	{
		/**
		 * FileCache constructor.
		 *
		 * @api __construct
		 * @param string $file File for the cache to read/write from.
		 * @param bool $autoPersist Should the cache auto-persist to the file.
		 * @param bool $cleanOnLoad Expired values will be cleaned on cache load.
		 * @throws InvalidFileException
		 */
		public function __construct(string $file, bool $autoPersist = true, bool $cleanOnLoad = true) {
			$this->data = [];
			$this->index = [];
			$this->file = $file;
			$this->autoPersist = $autoPersist;

			if (file_exists($file)) {
				if (!is_file($file))
					throw new InvalidFileException('Given cache source is not a file.');

				$raw = file_get_contents($file);
				if ($raw === false)
					throw new InvalidFileException('Unable to read data from cache source.');

				$parts = explode("\0", $raw);
				if (count($parts) !== 2)
					throw new InvalidFileException('Cache file does not contain expected data.');

				$this->data = unserialize(base64_decode($parts[0]));
				$this->index = unserialize(base64_decode($parts[1]));

				// Clean out expired values.
				if ($cleanOnLoad) {
					$time = time();
					foreach ($this->index as $key => $expire)
						if ($expire < $time)
							unset($this->data[$key], $this->index[$key]);
				}
			}
		}

		/**
		 * Persist the cache state to disk.
		 *
		 * @api persist
		 */
		public function persist() {
			$data = base64_encode(serialize($this->data));
			$index = base64_encode(serialize($this->index));

			file_put_contents($this->file, $data . "\0" . $index);
		}

		/**
		 * Obtain a value from the cache.
		 * Returns null if the item does not exist.
		 *
		 * @api __get
		 * @param string $key Key of the value.
		 * @return mixed|null
		 */
		public function __get(string $key) {
			// Check value expiry.
			if (array_key_exists($key, $this->index))
				if ($this->index[$key] < time())
					unset($this->index[$key], $this->data[$key]);

			return $this->data[$key] ?? null;
		}

		/**
		 * Store a value in the cache.
		 * Value will not expire, use store() if needed.
		 *
		 * @api __set
		 * @param string $key Key to store the value under.
		 * @param mixed $value Value to store in the cache.
		 */
		public function __set(string $key, $value) {
			$this->data[$key] = $value;
			unset($this->index[$key]);

			if ($this->autoPersist)
				$this->persist();
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
			$this->data[$key] = $value;

			// Store expiry value.
			if ($expire > 0) {
				// Calculate based on seconds.
				if ($expire < 60 * 60 * 24 * 30)
					$expire = time() + $expire;

				$this->index[$key] = $expire;
			} else {
				// Remove any expiration value associated with this key.
				unset($this->index[$key]);
			}

			if ($this->autoPersist)
				$this->persist();
		}

		/**
		 * Check if a value exists in the cache.
		 *
		 * @param string $key Key used to store the value.
		 * @return bool True if the key exists in the cache.
		 */
		public function exists(string $key): bool {
			return isset($this->data[$key]);
		}

		/**
		 * Remove an item stored in the cache.
		 *
		 * @api __unset
		 * @param string $key Key of the item to remove.
		 */
		public function __unset(string $key) {
			unset($this->data[$key], $this->index[$key]);

			if ($this->autoPersist)
				$this->persist();
		}

		/**
		 * Flush the cache, removing all stored data.
		 *
		 * @api flush
		 */
		public function flush() {
			$this->data = [];
			$this->index = [];

			if ($this->autoPersist)
				$this->persist();
		}

		/**
		 * Increase a numeric value in the cache.
		 *
		 * @api increment
		 * @param string $key Key of the value.
		 * @param int $weight How much to increment the value.
		 */
		public function increment(string $key, int $weight = 1) {
			$this->data[$key] = ($this->$key ?? 0) + $weight;

			if ($this->autoPersist)
				$this->persist();
		}

		/**
		 * Decrease a numeric value in the cache.
		 *
		 * @api decrement
		 * @param string $key Key of the value.
		 * @param int $weight How much to decrement the value.
		 */
		public function decrement(string $key, int $weight = 1) {
			$this->data[$key] = ($this->$key ?? 0) - $weight;

			if ($this->autoPersist)
				$this->persist();
		}

		/**
		 * @var string
		 */
		private $file;

		/**
		 * @var array
		 */
		private $data;

		/**
		 * @var array
		 */
		private $index;

		/**
		 * @var bool
		 */
		private $autoPersist;
	}