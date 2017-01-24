<?php
	namespace KrameWork;

	class KrameWorkSystemFeatureException extends \Exception {}

	/**
	 * Class System
	 * @package KrameWork
	 * Bootstrap and entry-point for the KrameWork system.
	 */
	class System {
		const FEATURE_AUTO_LOADING = 0x1;

		/**
		 * System constructor.
		 * @param int $flags System control flags.
		 */
		public function __construct($flags = 0) {
			$this->flags = $flags;

			if ($this->isFeatureEnabled(self::FEATURE_AUTO_LOADING)) {
				require_once("AutoLoader.php");
				$this->autoLoader = new AutoLoader();
			}
		}

		/**
		 * Check if a specific feature has been enabled.
		 * @param int $flag Feature flag.
		 * @return bool
		 */
		public function isFeatureEnabled($flag):bool {
			return $this->flags & $flag;
		}

		/**
		 * Obtain the auto-loader instance for the system.
		 * @return AutoLoader
		 * @throws KrameWorkSystemFeatureException
		 */
		public function getAutoLoader():AutoLoader {
			$this->verifyFeature(self::FEATURE_AUTO_LOADING);
			return $this->autoLoader;
		}

		/**
		 * Internal function to verify a feature is active before invoking.
		 * @param $flag
		 * @throws KrameWorkSystemFeatureException
		 */
		private function verifyFeature($flag) {
			if (!$this->isFeatureEnabled($flag))
				throw new KrameWorkSystemFeatureException("Cannot invoke instance for disabled feature.");
		}

		/**
		 * User-defined flags for the system.
		 * @var int
		 */
		private $flags;

		/**
		 * Internal auto-loader instance.
		 * @var AutoLoader
		 */
		private $autoLoader;
	}