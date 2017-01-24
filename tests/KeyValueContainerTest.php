<?php
	namespace KrameWork\Storage;
	require_once("src/Storage/KeyValueContainer.php");

	class KeyValueContainerTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test inserting, obtaining and deleting of items from a container.
		 */
		public function testBasicFunctions() {
			$container = new KeyValueContainer();

			// Insert & Retrieve
			$container->someValue = 42;
			$this->assertEquals(42, $container->someValue, "Retrieved value did not match inserted one.");

			// Delete
			unset($container->someValue);
			$this->assertEquals(null, $container->someValue, "Deleted value does not appear to be deleted.");

			unset($container);
		}

		/**
		 * Test the containers internal data array.
		 */
		public function testDataArray() {
			$container = new KeyValueContainer();

			$container->test = 500; // Insert generic test value.
			$arr = $container->asArray(); // Get the data array from the container.

			$this->assertTrue(is_array($arr), "Internal data array for container is not an array.");
			$this->assertArrayHasKey("test", $arr, "Data array does not contain test value.");
			$this->assertEquals(500, $arr["test"], "Data array contains the incorrect test value.");

			unset($container);
		}

		/**
		 * Test string-based serialization.
		 */
		public function testSerialization() {
			// Generic test data.
			$science = "We do what we must, because we can.";

			$container = new KeyValueContainer();
			$container->science = $science;

			$serialized = $container->serialize();
			$this->assertTrue(is_string($serialized), "Container serialization did not return a string.");

			$container = new KeyValueContainer(); // Work with a fresh container to be sure.
			$container->unserialize($serialized);
			$this->assertEquals($science, $container->science, "Unserialized value did not match original.");

			// Attempt the same unserialization, using the constructor.
			$container = new KeyValueContainer($serialized);
			$this->assertEquals($science, $container->science, "Unserialized value did not match original through constructor");

			unset($container);
		}

		/**
		 * Test data inheritance.
		 */
		public function testInheritance() {
			$source = new KeyValueContainer();
			$source->chickens = 5000; // Cluck, cluck.

			$target = new KeyValueContainer($source);
			$this->assertEquals(5000, $target->chickens, "Member inherited from another KeyValueContainer did not match original.");

			unset($target);
		}
	}