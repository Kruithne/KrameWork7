<?php
	require_once(__DIR__ . "/../src/Storage/JSONFile.php");

	use KrameWork\Storage\JSONFile;
	use KrameWork\Storage\KrameWorkFileException;

	class JSONFileTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Test raw data storage.
		 */
		public function testRawDataStorage() {
			$data = "You're never too old, too wacky, too wild, to pick up a book and read to a child.";
			$container = new JSONFile("someFile.json", false, false);
			$container->setRawData($data);

			$this->assertEquals($data, $container->getRawData(), "Retrieved data did not match inserted original.");
			unset($container);
		}

		/**
		 * Test container storage.
		 */
		public function testDataStorage() {
			$data = "Do you get to the cloud district very often?";
			$container = new JSONFile("someFile.json", true, false);

			$container->test = $data;
			$this->assertEquals($data, $container->test, "Retrieved container data did not match inserted original.");
			unset($container);
		}

		/**
		 * Test JSON file decoding.
		 */
		public function testDecoding() {
			$data = "From there to here, and here to there, funny things are everywhere.";
			$container = new JSONFile(__DIR__ . "/resources/test_json_file.json", true, false);

			$container->read();
			$this->assertEquals($data, $container->test, "Decoded data did not match expected string.");
			unset($container);
		}

		/**
		 * Test JSON file decoding with auto-load constructor.
		 */
		public function testAutoDecoding() {
			$data = "From there to here, and here to there, funny things are everywhere.";
			$container = new JSONFile(__DIR__ . "/resources/test_json_file.json", true, true);

			$this->assertEquals($data, $container->test, "Automatically decoded data did not match expected string.");
			unset($container);
		}

		/**
		 * Test JSON file encoding.
		 */
		public function testEncoding() {
			$file = "temp_json_file.json";
			if (file_exists($file))
				unlink($file);

			$data = ["A" => 52, "B" => "Some test", "C" => true, "D" => 0.314];
			$container = new JSONFile($file, true, false);
			foreach ($data as $key => $value)
				$container->$key = $value;

			$container->save();

			// Load data using a fresh instance.
			$container = new JSONFile($file, true, true);
			foreach ($data as $key => $value)
				$this->assertEquals($value, $container->$key, "Encoded value did not match original.");

			unlink($file);
			unset($container, $data);
		}

		/**
		 * Test exception is thrown when calling __set on uninitiated container.
		 */
		public function testNonInitiatedSet() {
			$container = new JSONFile("someFile.json", false, false);

			try {
				$container->testValue = true;
				$this->fail("JSONFile did not throw expected exception when invoking __set on non-initiated container.");
			} catch (KrameWorkFileException $e) {
				// Expected.
				unset($container);
			}
		}

		/**
		 * Test exception is thrown when calling __get on uninitiated container.
		 */
		public function testNonInitiatedGet() {
			$container = new JSONFile("someFile.json", false, false);

			try {
				$test = $container->testValue;
				$this->fail("JSONFile did not throw expected exception when invoking __get on non-initiated container.");
			} catch (KrameWorkFileException $e) {
				// Expected.
				unset($container);
			}
		}

		/**
		 * Test exception is thrown when calling __unset on uninitiated container.
		 */
		public function testNonInitiatedUnset() {
			$container = new JSONFile("someFile.json", false, false);

			try {
				unset($container->test);
				$this->fail("JSONFile did not throw exception when invoking __unset on a non-initiated container.");
			} catch (KrameWorkFileException $e) {
				// Expected.
				unset($container);
			}
		}

		/**
		 * Test that container values can be unset.
		 */
		public function testUnset() {
			$container = new JSONFile("someFile.json", true, false);
			$container->test = true;
			unset($container->test);

			$this->assertNull($container->test, "Unset value not equal to null in container.");
			unset($container);
		}

		/**
		 * Test object->array associative option.
		 */
		public function testOptionAssociative() {
			$str = "From there to here, and here to there, funny things are everywhere.";
			$container = new JSONFile(__DIR__ . "/resources/test_json_file.json", false, false);
			$container->setAssociative(true);
			$container->read();

			$data = $container->getData();
			$this->assertTrue(is_array($data), "Retrieve data is not an array.");
			$this->assertEquals($str, $data["test"], "Array->test does not contain original string.");
			unset($container);
		}

		/**
		 * Test JSON recursion depth.
		 */
		public function testOptionRecursion() {
			$container = new JSONFile(__DIR__ . "/resources/test_json_file.json", true, false);
			$container->setRecursionDepth(1);

			try {
				$container->read();
				$this->fail("Expected exception was not thrown when exceeding recursion depth.");
			} catch (KrameWorkFileException $e) {
				// Expected.
				unset($container);
			}
		}

		/**
		 * Test bit-mask option value for JSON decoding.
		 */
		public function testOptionMask() {
			$container = new JSONFile(__DIR__ . "/resources/test_json_file.json", true, false);
			$container->setOptions(JSON_BIGINT_AS_STRING);
			$container->read();

			$this->assertEquals("string", gettype($container->number), "Big number is not represented as a string.");
			unset($container);
		}
	}