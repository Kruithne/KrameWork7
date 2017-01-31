<?php
	require_once(__DIR__ . "/../src/AutoLoader.php");

	use KrameWork\AutoLoader;

	class AutoLoaderTest extends \PHPUnit_Framework_TestCase
	{
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

		/**
		 * Test trailing-slashes don't throw the auto-loader off.
		 */
		public function testBasicTrailing() {
			$loader = new AutoLoader(["tests/resources/"], null, 0);
			$test = new \TestClass();

			$this->assertEquals("Beep", $test->getTest(), "Auto-loader returned unexpected class.");
			$loader->disable();

			unset($loader, $test);
		}

		/**
		 * Test that providing an invalid source path throws an exception.
		 */
		public function testInvalidSourceException() {
			try {
				new AutoLoader(["somewhere/over/the/rainbow"], null, 0);
				$this->fail("Auto-loader did not throw InvalidSourcePathException with invalid source path.");
			} catch (\KrameWork\InvalidSourcePathException $e) {
				// expected.
			}
		}

		/**
		 * Test that recursive auto-loading works.
		 */
		public function testRecursiveLoading() {
			$loader = new AutoLoader(["tests/resources/AutoLoadClasses"], null, AutoLoader::RECURSIVE_SOURCING);
			$test = new DeepTestClass();

			$this->assertEquals("Eek!", $test->getTest(), "DeepTestClass did not return expected string.");

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
			$loader = new AutoLoader(["SomeNamespace" => "tests\\resources\\AutoLoadNamespaceTest"], null, 0);
			$test = new \SomeNamespace\TestClass();

			$this->assertEquals("Boop", $test->getTest(), "Auto-loader returned the wrong class");

			$loader->disable();
			unset($loader, $test);
		}
	}