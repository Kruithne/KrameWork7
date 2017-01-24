<?php
	namespace KrameWork;

	class ConfigLoaderException extends \Exception {}

	class ConfigLoader {
		/**
		 * ConfigLoader constructor.
		 */
		public function __construct() {
			$this->index = [];
		}

		/**
		 * Load configuration values from a flat file.
		 * @param string $file File path to load config from.
		 * @throws ConfigLoaderException
		 */
		public function loadFromFile($file) {
			if (!file_exists($file))
				throw new ConfigLoaderException("Specified config file does not exist.");

			$raw = file_get_contents($file);
			if ($raw === false)
				throw new ConfigLoaderException("Unable to read config file.");

			$data = json_decode($raw);
			if ($data === null)
				throw new ConfigLoaderException("Invalid JSON format in config file.");

			foreach ($data as $prop => $value)
				$this->__set($prop, $value);
		}

		/**
		 * Set a value in this configuration.
		 * @param string $name Key
		 * @param mixed $value Value
		 * @throws ConfigLoaderException
		 */
		public function __set($name, $value) {
			$this->index[$name] = $value;
		}

		/**
		 * Get a value from this configuration.
		 * @param $name
		 * @return mixed|null
		 */
		public function __get($name) {
			return $this->index[$name] ?? null;
		}

		private $index;
	}