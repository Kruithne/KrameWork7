<?php
	namespace KrameWork;

	/**
	 * Class System
	 * @package KrameWork
	 * Bootstrap and entry-point for the KrameWork system.
	 */
	class System {
		/**
		 * System constructor.
		 * @param mixed $config Path to config file, or key/value object.
		 */
		public function __construct($config = null) {
			require_once("SystemConfig.php");
			$this->config = new SystemConfig($config);
		}

		/**
		 * Get the configuration for this system instance.
		 * @return SystemConfig
		 */
		public function getConfig():SystemConfig {
			return $this->config;
		}

		/**
		 * @var SystemConfig
		 */
		private $config;
	}