<?php
	require_once(__DIR__ . '/../../../src/Caching/IDataCache.php');

	class DummyCache implements \KrameWork\Caching\IDataCache
	{
		public function __get(string $key) {
			return isset($this->data[$key]) ? $this->data[$key] : null;
		}

		public function __set(string $key, $value) {
			$this->data[$key] = $value;
		}

		public function store(string $key, $value, int $expire = 0) {
			$this->data[$key] = $value;
			$this->ttl[$key] = $expire;
		}

		public function exists(string $key): bool {
			return isset($this->data[$key]);
		}

		public function __unset(string $key) {
			unset($this->data[$key]);
		}

		public function flush(): void {
			$this->data = [];
		}

		public function increment(string $key, int $weight = 1) {
			$this->data[$key]++;
		}

		public function decrement(string $key, int $weight = 1) {
			$this->data[$key]--;
		}

		public $data = [];
		public $ttl = [];
	}