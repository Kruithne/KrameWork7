<?php
	use KrameWork\AutoLoader;

	require_once("src/AutoLoader.php");

	class AutoLoaderTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test loading of a basic global class (forward slashes).
		 */
		public function testBasicClassLoadFS() {
			$loader = new AutoLoader(["tests/resources"], null, 0);
			$test = new \TestClass();

			$this->assertEquals("Beep", $test->getTest(), "Auto-loader returned unexpected class.");

			$loader->disable();
			unset($loader, $test);
		}

		/**
		 * Test loading of a basic global class (back slashes).
		 */
		public function testBasicClassLoadBS() {
			$loader = new AutoLoader(["tests\\resources"], null, 0);
			$test = new \TestClass();

			$this->assertEquals("Beep", $test->getTest(), "Auto-loader returned unexpected class.");

			$loader->disable();
			unset($loader, $test);
		}

		public function testBasicTrailing() {
			$loader = new AutoLoader(["tests/resources/"], null, 0);
			$test = new \TestClass();

			$this->assertEquals("Beep", $test->getTest(), "Auto-loader returned unexpected class.");
			$loader->disable();

			unset($loader, $test);
		}

		/**
		 * Test loading of a class within a namespace.
		 */
		public function testNamespaceClassLoad() {
			$loader = new AutoLoader(["tests/resources"], null, 0);
			$test = new \NamespaceTest\TestClass();

			$this->assertEquals("Honk", $test->getTest(), "Auto-loader returned the wrong class!");

			$loader->disable();
			unset($loader, $test);
		}

		/**
		 * Test loading of a mapped namespace class.
		 */
		public function testMappedNamespaceClassLoad() {
			$loader = new AutoLoader([["SomeNamespace", "tests\\resources\\AutoLoadNamespaceTest"]], null, 0);
			$test = new \SomeNamespace\TestClass();

			$this->assertEquals("Boop", $test->getTest(), "Auto-loader returned the wrong class");

			$loader->disable();
			unset($loader, $test);
		}
	}