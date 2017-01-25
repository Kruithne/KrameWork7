<?php
	namespace KrameWork;

	require_once("src/SystemConfig.php");

	class SystemConfigTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test the basic functionality of the SystemConfig class.
		 */
		public function testBasics() {
			// Load without a source.
			$config = new SystemConfig();

			// Check that all default values return from the config.
			foreach (KRAMEWORK_CONFIG_DEFAULTS as $confKey => $confValue)
				$this->assertEquals($confValue, $config->$confKey, "Expected default value does not exist in config.");

			// Load with our own values.
			$custom = ["someUnusedTestingValue" => 42];
			$config = new SystemConfig($custom);

			// Check that our default values still work.
			foreach (KRAMEWORK_CONFIG_DEFAULTS as $confKey => $confValue)
				$this->assertEquals($confValue, $config->$confKey, "Expected default value does not exist in config.");

			// Check our new values exist, too.
			foreach ($custom as $confKey => $confValue)
				$this->assertEquals($confValue, $config->$confKey, "Expected new value does not exist in config.");
		}
	}