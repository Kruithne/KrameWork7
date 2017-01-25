<?php
	namespace KrameWork;

	require_once("src/System.php");

	class SystemTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test basic functionality of the system class.
		 */
		public function testSystemBasics() {
			$system = new System();

			$this->assertTrue($system->getConfig() instanceof SystemConfig, "System config is not a SystemConfig");
		}
	}