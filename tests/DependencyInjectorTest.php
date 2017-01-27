<?php
	require_once("src/DependencyInjector.php");

	use KrameWork\DependencyInjector;
	use KrameWork\KrameWorkDependencyInjectorException;

	interface DITestInterface {}
	class DITestClass implements DITestInterface {}

	class DependencyInjectorTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test functionality of dependency injectors class resolution.
		 */
		public function testClassResolution() {
			$err = "Class name did not resolve properly.";
			$injector = new DependencyInjector(null, DependencyInjector::BIND_INTERFACES);

			// Class name should resolve to itself.
			$this->assertEquals("Exception", $injector->resolveClassName("Exception"), $err);

			// Array of strings should resolve equally.
			$classNames = ["MyClassA", "MyClassB"];
			$resolved = $injector->resolveClassName($classNames);
			$this->assertEquals("MyClassA", $resolved[0], $err);
			$this->assertEquals("MyClassB", $resolved[1], $err);

			// Passing a class should resolve to the class name.
			$e = new \Exception();
			$this->assertEquals("Exception", $injector->resolveClassName($e), $err);

			// Ensure invalid input throws expected exception.
			try {
				$injector->resolveClassName(1);
				$this->fail("Injector did not throw exception on invalid class resolution.");
			} catch (KrameWorkDependencyInjectorException $ex) {
				// Expected.
			}

			// Test class binding.
			$injector->bindInterface("IException", "Exception");
			$this->assertEquals("Exception", $injector->resolveClassName("IException"), $err);
		}

		/**
		 * Test functionality of adding classes to the injector.
		 */
		public function testComponentAdding() {
			$injector = new DependencyInjector(null, DependencyInjector::BIND_INTERFACES);
			$injector->addComponent("DITestClass");

			try {
				$injector->addComponent("DITestClass");
				$this->fail("Duplicate class exception was not thrown when expected.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}
		}
	}