<?php
	namespace KrameWork;
	use KrameWork\Storage\JSONFile;
	use KrameWork\Storage\KeyValueContainer;
	use KrameWork\Storage\KrameWorkFileException;

	require_once("Storage/JSONFile.php");

	define("KRAMEWORK_CONFIG_DEFAULTS", [
		"enableClassAutoLoading" => true
	]);

	class SystemConfig extends JSONFile {
		/**
		 * SystemConfig constructor.
		 * @param mixed $config Configuration.
		 * @throws KrameWorkFileException
		 */
		public function __construct($config = null) {
			if ($config !== null) {
				if (is_string($config)) {
					// Attempt to load config from file.
					parent::__construct($config, true, true);
				} else {
					// Obtain config key/values from given object/array.
					$this->setRawData(new KeyValueContainer($config));
				}
				return;
			}
			parent::__construct(null, true, false);
		}

		/**
		 * Get a value from the config.
		 * @param string $key
		 * @return mixed|null
		 * @throws KrameWorkFileException
		 */
		public function __get($key) {
			return parent::__get($key) ?? KRAMEWORK_CONFIG_DEFAULTS[$key] ?? null;
		}
	}