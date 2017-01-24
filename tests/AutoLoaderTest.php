<?php
	namespace KrameWork;
	require_once("src/System.php");

	class AutoLoaderTest extends \PHPUnit_Framework_TestCase
	{
		protected function setUp() {
			$this->system = new System(System::FEATURE_AUTO_LOADING);
		}

		/**
		 * Ensure the auto-loader is disabled when it should be and cannot be obtained from the system
		 * during it's disabled state.
		 */
		public function testDisabled() {
			$system = new System(0); // System with all features disabled.
			$this->assertEquals(false, $system->isFeatureEnabled(System::FEATURE_AUTO_LOADING), "System claims auto-loader is not disabled when it should be.");

			try {
				$system->getAutoLoader();
				$this->fail("Invoking disabled auto-loader did not throw expected exception.");
			} catch (KrameWorkSystemFeatureException $e) {}

			unset($system);
		}

		/**
		 * Test that the auto-loader loader is enabled when it should be and the system provides a valid
		 * instance of the auto-loader when requested.
		 */
		public function testEnabled() {
			$this->assertEquals(true, $this->system->isFeatureEnabled(System::FEATURE_AUTO_LOADING), "System claims auto-loader is not enabled when it should be.");

			try {
				$loader = $this->system->getAutoLoader();
				if (!$loader instanceof AutoLoader)
					$this->fail("Auto-loader instance provided by system is not of KrameWork\\AutoLoader type");

			} catch (KrameWorkSystemFeatureException $e) {
				$this->fail("Feature invoke exception thrown when trying to obtain auto-loader from system.");
			}
		}

		/**
		 * @var System
		 */
		private $system;
	}