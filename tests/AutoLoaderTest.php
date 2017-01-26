<?php
	namespace KrameWork;

	require_once("src/AutoLoader.php");

	class AutoLoaderTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test normal operation of the auto-loader.
		 */
		public function testAutoLoader() {
			// Test loading a class without a namespace.
			$loader = new AutoLoader(["tests\\resources"], null, 0);
			$test = new \TestClass();

			$this->assertEquals("Beep", $test->getTest(), "Auto-loader returned the wrong class!");
			$loader->disable();

			// Test loading a class with a structure-mapped namespace.
			$loader = new AutoLoader(["tests\\resources"], null, 0);
			$test = new \NamespaceTest\TestClass();

			$this->assertEquals("Honk", $test->getTest(), "Auto-loader returned the wrong class!");
			$loader->disable();

			// Test loading a class that doesn't follow directory structure.
			$loader = new AutoLoader([["SomeNamespace", "tests\\resources\\AutoLoadNamespaceTest"]], null, 0);
			$test = new \SomeNamespace\TestClass();

			$this->assertEquals("Boop", $test->getTest(), "Auto-loader returned the wrong class");
			$loader->disable();
		}
	}