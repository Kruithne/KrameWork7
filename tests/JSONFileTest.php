<?php
	namespace KrameWork\Storage;
	require_once("src/Storage/JSONFile.php");

	class JSONFileTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test basic data storage for the JSON file container.
		 */
		public function testDataStorage() {
			$data = "You're never too old, too wacky, too wild, to pick up a book and read to a child.";
			$container = new JSONFile(null, false);
			$container->setRawData($data);

			$this->assertEquals($data, $container->getRawData(), "Retrieved data did not match inserted original.");
		}

		/**
		 * Validate the JSON encoding/decoding functions of the JSON file container.
		 */
		public function testJSONConversion() {
			$data = ["Eggs" => 5, "Ham" => 8];
			$container = new JSONFile(null, false);
			$container->setRawData($data);

			$json = $container->compile();

			// Decode outside of the container, for sanity sake.
			$decode = json_decode($json);
			$this->assertNotFalse($decode, "JSON decoding error.");
			$this->assertTrue(is_object($decode), sprintf("Decoded data is of type '%s', not expected 'object'.", gettype($decode)));
			foreach ($data as $key => $value) {
				$this->assertObjectHasAttribute($key, $decode, "Decoded array is missing key from original array.");
				$this->assertEquals($value, $decode->$key, "Decoded array has incorrect value compared to original array.");
			}

			// Repeat the same process, using the container to decode this time.
			$container = new JSONFile(null, false);
			$container->parse($json);

			$decode = $container->getRawData();
			$this->assertNotFalse($decode, "JSON decoding error.");
			$this->assertTrue(is_object($decode), sprintf("Decoded data is of type '%s', not expected 'object'.", gettype($decode)));
			foreach ($data as $key => $value) {
				$this->assertObjectHasAttribute($key, $decode, "Decoded array is missing key from original array.");
				$this->assertEquals($value, $decode->$key, "Decoded array has incorrect value compared to original array.");
			}
		}

		/**
		 * Test exceptions thrown during internal data invoking.
		 */
		public function testNonInitiatedInvoke() {
			// Attempt to produce the exceptions.
			$container = new JSONFile(null, false);

			try {
				$test = $container->testValue;
				$this->fail("JSONFile did not throw exception when invoking __get on non-initiated container.");
			} catch (KrameWorkFileException $e) {
				// Expected.
			}

			try {
				$container->testValue = true;
				$this->fail("JSONFile did not throw exception when invoking __set on a non-initiated container.");
			} catch (KrameWorkFileException $e) {
				// Expected.
			}

			try {
				unset($container->testValue);
				$this->fail("JSONFile did not throw exception when invoking __unset on a non-initiated container.");
			} catch (KrameWorkFileException $e) {
				// Expected.
			}

			// Repeat process without triggering exceptions.
			$data = "A person's a person, no matter how small.";
			$container = new JSONFile(null, true);
			$container->stuff = $data;

			$this->assertNull($container->things, "Default value for a non-existing key is not null.");
			$this->assertEquals($data, $container->stuff, "Retrieved data did not match inserted original.");

			unset($container->stuff);
			$this->assertNull($container->stuff, "Retrieved data after unset is not null.");
		}

		/**
		 * Test functionality of JSONFile pass-through to internal container.
		 */
		public function testContainerPassThrough() {
			$data = "You make 'em, I amuse 'em.";
			$container = new JSONFile(null, true);
			$container->things = $data;

			$this->assertNull($container->stuff, "Initial value of a non-set key is not null");
			$this->assertEquals($data, $container->things, "Retrieved value does not match inserted original.");

			unset($container->things);
			$this->assertNull($container->things, "Unset value from container does not equal null.");
		}

		/**
		 * Test file loading capabilities of a JSONFile object.
		 */
		public function testFileLoading() {
			$str = "From there to here, and here to there, funny things are everywhere.";
			$src = "tests/resources/test_json_file.json";

			// Test using the read() call.
			$container = new JSONFile(null, false);
			$container->read($src);

			$data = $container->getRawData();
			$this->assertTrue(is_object($data), sprintf("Internal data type is '%s', not expected 'object'", gettype($data)));
			$this->assertObjectHasAttribute("test", $data, "Data does not contain expected 'test' attribute.");
			$this->assertEquals($str, $data->test, "'test' value inside data does not match original.");

			// Test using constructor call.
			$container = new JSONFile($src, false);
			$data = $container->getRawData();

			$this->assertTrue(is_object($data), sprintf("Internal data type is '%s', not expected 'object'", gettype($data)));
			$this->assertObjectHasAttribute("test", $data, "Data does not contain expected 'test' attribute.");
			$this->assertEquals($str, $data->test, "'test' value inside data does not match original.");
		}

		/**
		 * Test internal data container after loading.
		 */
		public function testLoadedContainer() {
			$str = "From there to here, and here to there, funny things are everywhere.";
			$src = "tests/resources/test_json_file.json";

			$container = new JSONFile($src, true);
			$this->assertEquals($container->test, $str, "Retrieved value does not equal expected original.");
		}

		/**
		 * Test writing to JSON file.
		 */
		public function testWriting() {
			$str = "You can get help from teachers, but you are going to have to learn a lot by yourself, sitting alone in a room.";
			$src = "JSONFileTest.WriteExperiment.tmp";

			// Remove possible fragments from previous broken tests.
			if (file_exists($src))
				unlink($src);

			// Write JSON to file.
			$container = new JSONFile(null, true);
			$container->stuff = $str;
			$container->save($src);

			// Load it back in.
			$container = new JSONFile($src, true);
			$this->assertEquals($str, $container->stuff, "Loaded JSON value does not match expected original.");

			unlink($src); // Clean-up.
		}

		/**
		 * Test object->array associative option.
		 */
		public function testOptionAssociative() {
			$str = "From there to here, and here to there, funny things are everywhere.";
			$src = "tests/resources/test_json_file.json";

			$container = new JSONFile(null, false);
			$container->setAssociative(true);
			$container->read($src);

			$data = $container->getRawData();
			$this->assertTrue(is_array($data), "Retrieved data is not an array.");
			$this->assertEquals($str, $data["test"], "Array->test does not contain original string.");
		}

		/**
		 * Test JSON recursion depth.
		 */
		public function testOptionRecursion() {
			$test = json_encode([[[]]]); // 3-level array.

			$container = new JSONFile(null, false);
			$container->setRecursionDepth(1);

			try {
				$container->parse($test);
				$this->fail("Expected exception was not thrown when exceeding recrusion depth.");
			} catch (KrameWorkFileException $e) {
				// Expected.
			}
		}

		/**
		 * Test bit-mask option value for JSON decoding.
		 */
		public function testOptionMask() {
			$str = "From there to here, and here to there, funny things are everywhere.";
			$src = "tests/resources/test_json_file.json";

			$container = new JSONFile(null, false);
			$container->setOptions(JSON_BIGINT_AS_STRING);
			$container->read($src);

			$data = $container->getRawData();
			$type = gettype($data->number);
			$this->assertObjectHasAttribute("number", $data, "Object is missing expected 'number' attribute.");
			$this->assertEquals("string", $type, "Big number is not represented as a string.");
		}
	}