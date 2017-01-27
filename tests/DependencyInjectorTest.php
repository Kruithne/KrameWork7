<?php
	require_once("src/DI/DependencyInjector.php");

	use KrameWork\DI\DependencyInjector;
	use KrameWork\DI\KrameWorkDependencyInjectorException;

	interface DITestInterface {
		public function test():string;
	}

	interface DITestInterfaceA {}

	class DITestClass implements DITestInterface {
		public function __construct() {
			$this->id = self::$increment++;
		}

		public function test():string {
			return "You can call, but I probably won't hear you.";
		}

		public function getID():int {
			return $this->id;
		}

		private $id;
		private static $increment = 1;
	}

	class DINeedyTestClass {
		public function __construct(DITestInterface $interface) {
			$this->test = $interface->test();
		}

		public function test():string {
			return $this->test;
		}

		private $test;
	}

	class DICyclicTestClass {
		public function __construct(DICyclicTestClass $class) {
			// I am a bork.
		}
	}

	class DIBrokenTestClass {
		public function __construct($something) {
			// I am a bork.
		}
	}

	abstract class DIAbstractTestClass {
		// You can't make me, bully.
	}

	class DependencyInjectorTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test that a class name (string) resolves to itself.
		 */
		public function testBasicClassResolution() {
			$injector = new DependencyInjector();
			$this->assertEquals("Exception", $injector->resolveClassName("Exception"), "Class name did not resolve properly");
			unset($injector);
		}

		/**
		 * Test that an array of class names (strings) resolves to themselves.
		 */
		public function testArrayClassResolution() {
			$classNames = ["MyClassA", "MyClassB"];
			$injector = new DependencyInjector();
			$resolved = $injector->resolveClassName($classNames);

			for ($i = 0; $i < count($classNames); $i++)
				$this->assertEquals($classNames[$i], $resolved[$i], "Class name did not resolve properly");

			unset($injector, $classNames);
		}

		/**
		 * Test that a multi-dimensional array of class names will resolve properly.
		 */
		public function testMultidimensionalArrayClassResolution() {
			$classNames = [["MyClassA", "MyClassB"],["MyClassC"]];
			$classNamesFlat = ["MyClassA", "MyClassB", "MyClassC"];

			$injector = new DependencyInjector();
			$resolved = $injector->resolveClassName($classNames);

			for ($i = 0; $i < count($classNames); $i++)
				$this->assertEquals($classNamesFlat[$i], $resolved[$i], "Class name did not resolve properly.");

			unset($injector, $classNames, $classNamesFlat);
		}

		/**
		 * Test that an object resolves to its own class name.
		 */
		public function testObjectClassResolution() {
			$injector = new DependencyInjector();
			$this->assertEquals("Exception", $injector->resolveClassName(new \Exception()), "Class name did not resolve properly.");
			unset($injector);
		}

		/**
		 * Test if an array of mixed types will resolve class names properly.
		 */
		public function testMixedClassResolution() {
			$mixed = ["MyClassA", ["MyClassB", "MyClassC", ["MyClassD"]], new \Exception()];
			$mixedFlat = ["MyClassA", "MyClassB", "MyClassC", "MyClassD", "Exception"];

			$injector = new DependencyInjector();
			$resolved = $injector->resolveClassName($mixed);

			for ($i = 0; $i < count($mixedFlat); $i++)
				$this->assertEquals($mixedFlat[$i], $resolved[$i], "Class name did not resolve properly.");

			unset($injector, $mixed, $mixedFlat);
		}

		/**
		 * Test that invalid input to class resolution will throw an exception.
		 */
		public function testInvalidClassResolution() {
			$injector = new DependencyInjector();
			try {
				$injector->resolveClassName(1);
				$this->fail("Injector did not throw exception on invalid class resolution.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}
			unset($injector);
		}

		/**
		 * Test that an interface will resolve to a bound class.
		 */
		public function testInterfaceResolution() {
			$injector = new DependencyInjector();
			$injector->bindInterface("IException", "Exception");
			$this->assertEquals("Exception", $injector->resolveClassName("IException"), "Interface did not resolve to class name.");
			unset($injector);
		}

		/**
		 * Test basic component adding/retrieving.
		 */
		public function testBasicComponentAdding() {
			$str = "You can call, but I probably won't hear you.";
			$injector = new DependencyInjector();

			$injector->addComponent("DITestClass");
			$component = $injector->getComponent("DITestClass", false); /** @var DITestInterface $component */

			$this->assertNotNull($component, "Component was not returned from injector.");
			$this->assertEquals($str, $component->test(), "Incorrect component returned from injector.");

			unset($injector, $component);
		}

		/**
		 * Test that the auto-add flag for component retrieval works.
		 */
		public function testComponentAutoAdding() {
			$str = "You can call, but I probably won't hear you.";
			$injector = new DependencyInjector();

			$component = $injector->getComponent("DITestClass", true); /** @var DITestInterface $component */

			$this->assertNotNull($component, "Component was not returned from injector.");
			$this->assertEquals($str, $component->test(), "Incorrect component returned from injector.");

			unset($injector, $component);
		}

		/**
		 * Test that an exception is thrown when we try to retrieve a missing component without auto-add.
		 */
		public function testMissingComponent() {
			$injector = new DependencyInjector();

			try {
				$injector->getComponent("DITestClass", false);
				$this->fail("Missing class did not throw exception from injector.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test that when we retrieve an object by interface, it returns the correct instance.
		 */
		public function testRetrieveInterfaceComponent() {
			$str = "You can call, but I probably won't hear you.";
			$injector = new DependencyInjector(null, DependencyInjector::DEFAULT_FLAGS | DependencyInjector::BIND_INTERFACES);

			$injector->addComponent("DITestClass"); // Implements DITestInterface
			$component = $injector->getComponent("DITestInterface"); /** @var DITestInterface $component */

			$this->assertNotNull($component, "Interface instance returned from injector was null.");
			$this->assertEquals($str, $component->test(), "Incorrect interface instance returned from injector.");

			unset($injector, $component);
		}

		/**
		 * Test that when we retrieve a non-constructed component numerous times, we always get the same instance.
		 */
		public function testRepeatedComponentRetrieval() {
			$injector = new DependencyInjector();
			$injector->addComponent("DITestClass");

			$componentA = $injector->getComponent("DITestClass", false); /** @var DITestClass $componentA */
			$componentB = $injector->getComponent("DITestClass", false); /** @var DITestClass $componentB */

			$this->assertEquals($componentA->getID(), $componentB->getID(), "Constructed instances from injector do not match.");

			unset($injector, $componentA, $componentB);
		}

		/**
		 * Test that when we retrieve a non-constructed, non-added component using auto-add, we get the same instance.
		 */
		public function testRepeatedAutoAddComponentRetrieval() {
			$injector = new DependencyInjector();

			$componentA = $injector->getComponent("DITestClass", true); /** @var DITestClass $componentA */
			$componentB = $injector->getComponent("DITestClass", true); /** @var DITestClass $componentB */

			$this->assertEquals($componentA->getID(), $componentB->getID(), "Constructed instances from injector do not match.");

			unset($injector, $componentA, $componentB);
		}

		/**
		 * Test that when we add a pre-constructed object, we get the same instance back.
		 */
		public function testAddRetrieveObject() {
			$injector = new DependencyInjector();
			$obj = new DITestClass();

			$injector->addComponent($obj);
			$component = $injector->getComponent("DITestClass"); /** @var DITestClass $component */

			$this->assertEquals($obj->getID(), $component->getID(), "Instance returned from injector did not match orignal.");

			unset($injector, $component);
		}

		/**
		 * Test that when an object is constructed, it is given the correct requirements.
		 */
		public function testObjectDependencies() {
			$str = "You can call, but I probably won't hear you.";

			$injector = new DependencyInjector();
			$injector->addComponent("DITestClass");
			$injector->addComponent("DINeedyTestClass");

			$component = $injector->getComponent("DINeedyTestClass", false); /** @var DINeedyTestClass $component */
			$this->assertNotNull($component, "Returned instance of DINeedyTestClass was null?");
			$this->assertEquals($str, $component->test(), "Needy class did not get constructed properly.");

			unset($injector, $component);
		}

		/**
		 * Test that when an object is constructed with missing classes, they are automatically added.
		 */
		public function testObjectDependenciesAutoAdd() {
			$str = "You can call, but I probably won't hear you.";
			$injector = new DependencyInjector(null, DependencyInjector::DEFAULT_FLAGS | DependencyInjector::AUTO_ADD_DEPENDENCIES);

			$injector->addComponent("DINeedyTestClass");
			$injector->bindInterface("DITestInterface", "DITestClass");
			$component = $injector->getComponent("DINeedyTestClass", false); /** @var DINeedyTestClass $component */

			$this->assertNotNull($component, "Returned instance of DINeedyTestClass was null?");
			$this->assertEquals($str, $component->test(), "Needy class did not get constructed properly.");

			unset($injector, $component);
		}

		/**
		 * Test that when trying to construct a dependency without AUTO_ADD_DEPENDENCIES, we throw an exception.
		 */
		public function testObjectDependenciesWithoutAutoAdd() {
			$injector = new DependencyInjector(null, DependencyInjector::DEFAULT_FLAGS & ~DependencyInjector::AUTO_ADD_DEPENDENCIES);

			$injector->addComponent("DINeedyTestClass");
			$injector->bindInterface("DITestInterface", "DITestClass");

			try {
				$injector->getComponent("DINeedyTestClass", false);
				$this->fail("Injector did not throw exception when trying to construct object with missing dependencies.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test that an exception is thrown when trying to construct cyclic dependencies.
		 */
		public function testCyclicDependencyException() {
			$injector = new DependencyInjector();
			$injector->addComponent("DICyclicTestClass");

			try {
				$injector->getComponent("DICyclicTestClass");
				$this->fail("Injector did not throw cyclic dependency exception when expected.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test exception is thrown when constructing object with undefined dependencies.
		 */
		public function testBrokenConstruction() {
			$injector = new DependencyInjector();
			$injector->addComponent("DIBrokenTestClass");

			try {
				$injector->getComponent("DIBrokenTestClass");
				$this->fail("Injector did not throw exception when constructing broken class.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test exception is thrown when trying to bind to an invalid object.
		 */
		public function testBindInvalidInterface() {
			$injector = new DependencyInjector();

			try {
				$injector->bindInterface("ISomething", 1);
				$this->fail("Injector did not throw exception when binding to invalid type.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test for instantiation exception when constructing instantiable class.
		 */
		public function testInvalidInstantiation() {
			$injector = new DependencyInjector();
			$injector->addComponent("DIAbstractTestClass");

			try {
				$injector->getComponent("DIAbstractTestClass");
				$this->fail("Injector did not throw instantiation exception when constructing abstract class.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test duplicate component handling.
		 */
		public function testDuplicateComponent() {
			$class = new class {};
			$injector = new DependencyInjector();
			$injector->addComponent([new $class, new $class]);

			try {
				$injector->getComponent(get_class($class), false);
				$this->fail("Injector did not throw exception when retrieving duplicate components with getComponent()");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test retrieval of duplicate components using getComponents().
		 */
		public function testDuplicateComponentRetrieval() {
			$class = new class {};
			$componentA = new $class;
			$componentB = new $class;

			$injector = new DependencyInjector();
			$injector->addComponent([$componentA, $componentB]);

			$components = $injector->getComponents(get_class($class));
			$this->assertCount(2, $components, "Injector did not return the expected amount of instances.");
			foreach ($components as $component)
				$this->assertInstanceOf(get_class($class), $component, "Unexpected class returned.");

			unset($injector, $componentA, $componentB);
		}

		/**
		 * Test exception is thrown when retrieving an interface with multiple instances bound.
		 */
		public function testDuplicateBinding() {
			$classA = new class implements DITestInterfaceA {};
			$classB = new class implements DITestInterfaceA {};

			$injector = new DependencyInjector();
			$injector->addComponent([$classA, $classB]);

			try {
				$injector->getComponent("DITestInterfaceA");
				$this->fail("Injector did not throw exception when recalling interface with multiple instances bound.");
			} catch (KrameWorkDependencyInjectorException $e) {
				// Expected.
			}

			unset($injector);
		}

		/**
		 * Test retrieval of multiple instances bound by the same interface.
		 */
		public function testDuplicateBindingRetrieval() {
			$classA = new class implements DITestInterfaceA {};
			$classB = new class implements DITestInterfaceA {};

			$injector = new DependencyInjector();
			$injector->addComponent([$classA, $classB]);

			$components = $injector->getComponents("DITestInterfaceA");
			$this->assertCount(2, $components, "Injector did not return expected amount of interface bound instances.");
			unset($injector);
		}
	}