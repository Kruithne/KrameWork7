<?php
	require_once(__DIR__ . "/../src/Storage/File.php");
	require_once(__DIR__ . "/../src/Storage/File.php");

	use KrameWork\Storage\File;

	class FileTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test reading functionality.
		 */
		public function testReading() {
			$src = "tests/resources/test_text_file.txt";

			$file = new File($src, true);

			$text = "Unless someone like you cares a whole awful lot, nothing is going to get better. It's not.";
			$this->assertEquals($text, $file->getData(), "Returned file data does not equal original text.");

			unset($file);
		}

		/**
		 * Test file loading options/name caching.
		 */
		public function testNoAutoLoad() {
			$src = "tests/resources/test_text_file.txt";

			$file = new File($src, false);
			$this->assertEquals("", $file->getData(), "File data was loaded when we specified not to.");

			$text = "Unless someone like you cares a whole awful lot, nothing is going to get better. It's not.";

			$file->read();
			$this->assertEquals($text, $file->getData(), "File data did not match expected original.");

			unset($file);
		}

		/**
		 * Test writing some data to disk.
		 */
		public function testWriting() {
			$src = "GenericFileTest.Experiment.tmp";
			$data = "I like nonsense; it wakes up the brain cells.";

			$file = new File($src, false);
			$file->setData($data);
			$file->save(null, true);

			$this->assertFileExists($src, "File was not written to disk.");

			$file = new File($src, true); // Fresh instance.
			$this->assertEquals($data, $file->getData(), "Data from file did not match what we tried to write.");

			unset($file);
			unlink($src); // Clean-up after ourselves.
		}

		/**
		 * Test writing to a file without any data.
		 */
		public function testBlankWrite() {
			$src = "GenericFileTest.BlankExperiment.tmp";

			$file = new File($src, false);
			$file->save(null, true);

			$this->assertFileExists($src, "File was not written to disk.");

			$file = new File($src, true); // Fresh instance.
			$this->assertEquals("", $file->getData(), "File data did not match the empty string we expected");

			unset($file);
			unlink($src);
		}

		/**
		 * Test overwriting existing files works as intended.
		 */
		public function testOverwrite() {
			$src = "GenericFileTest.OverwriteExperiment.tmp";
			$data = "You have brains in your head. You have feet in your shoes.";

			// Check for possible leftovers from broken test.
			if (file_exists($src))
				unlink($src);

			$file = new File($src, false);
			$file->setData($data);
			$file->save(null, true); // Should not throw exception, since file does not exist.

			$overwrite = "You can steer yourself in any direction you choose.";

			try {
				// This data should not get saved to the disk.
				$file->setData($overwrite);

				$file->save($src, false);
				$this->fail("Attempt to overwrite a file without overwrite flag was not stopped by exception.");
			} catch (\KrameWork\Storage\FileWriteException $e) {
				// Expected, since we tried to overwrite without specifying.
			}

			$file = new File($src, true);

			$this->assertNotEquals($overwrite, $file->getData(), "File data was overwritten.");
			$this->assertEquals($data, $file->getData(), "File data does not match original or overwritten?");

			$final = "You're on your own, and you know what...";
			$file->setData($final);
			$file->save($src, true);

			$file = new File($src, true);
			$this->assertEquals($final, $file->getData(), "File did not get overwritten when we intended it to.");

			unlink($src);
		}

		/**
		 * Test exists() functionality.
		 */
		public function testFileExists() {
			$src = "GenericFileTest.NonExistent";
			@unlink($src);

			$file = new File("GenericFileTest.NonExistent", false);
			$this->assertFalse($file->exists(), "Container claims file exists when it should not.");

			$file = new File("tests/resources/test_text_file.txt");
			$this->assertTrue($file->exists(), "Container claims file does not exist when it should.");
		}

		/**
		 * Test file touching works as expected.
		 */
		public function testFileTouch() {
			$src = "TouchTestFIle.tmp";
			@unlink($src);

			$this->assertFileNotExists($src, "File exists before touch?");

			$file = new File($src, false, true);
			$this->assertFileExists($src, "File was not touched.");

			unlink($src);
			unset($file);
		}

		/**
		 * Test file deletion works as expected.
		 */
		public function testFileDelete() {
			$src = "DeleteFileTest.tmp";

			$file = new File($src, false, true);
			$file->delete();
			$this->assertFileNotExists($src, "File exists after being deleted?");

			unset($src);
		}

		/**
		 * Test file size returns as expected.
		 */
		public function testGetSize() {
			$file = new File("tests/resources/test_json_file.json", false);
			$this->assertEquals(148, $file->getSize());
			unset($file);
		}

		/**
		 * Test the file extension returns as expected.
		 */
		public function testGetExtension() {
			$file = new File("tests/resources/test_json_file.json", false);
			$this->assertEquals("json", $file->getExtension());
			unset($file);
		}

		/**
		 * Test File function getFileType().
		 */
		public function testFileType() {
			$file = new File("tests/resources/test_json_file.json", false);
			$this->assertEquals("text/plain", $file->getFileType(), "Unexpected file type!");

			$file = new File("tests/resources/HTTPContextTest/tmp/phpbF6s1h", false);
			$this->assertEquals("image/png", $file->getFileType());

			unset($file);
		}

		/**
		 * Test the forceRead flag for getData() calls.
		 */
		public function testForceRead() {
			$str = "Unless someone like you cares a whole awful lot, nothing is going to get better. It's not.";
			$file = new File("tests/resources/test_text_file.txt", false);

			$this->assertEquals($str, $file->getData(true));
			unset($file);
		}

		/**
		 * Test functionality of getBase64Data().
		 */
		public function testGetBase64Data() {
			$str = "Unless someone like you cares a whole awful lot, nothing is going to get better. It's not.";
			$encoded = base64_encode($str);

			$file = new File("tests/resources/test_text_file.txt", false);
			$this->assertEquals($encoded, $file->getBase64Data(true));

			unset($file);
		}
	}