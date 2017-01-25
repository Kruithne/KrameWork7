<?php
	namespace KrameWork\Storage;
	require_once("src/Storage/BaseFile.php");
	require_once("src/Storage/GenericFile.php");

	class GenericFileTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test basic functionality of the class.
		 */
		public function testFoundation() {
			$file = new GenericFile(); // Create without initial file.
			$this->assertEquals("", $file->getData(), "Initial file data expected to be empty, was not!");
			$this->assertEquals("", $file->compile(), "Initial compiled data expected to be empty, was not!");

			$subject = "Today you are you! That is truer than true! There is no one alive who is you-er than you!";
			$file->setData($subject);

			$this->assertEquals($subject, $file->getData(), "File data did not match the original data we set.");
			$this->assertEquals($subject, $file->compile(), "Compiled file data did not match original data we set.");

			unset($file);
		}

		/**
		 * Test reading functionality.
		 */
		public function testReading() {
			$src = "tests/resources/test_text_file.txt";

			$file = new GenericFile();
			$file->read($src); // Throws exceptions on IO errors.

			$alt = new GenericFile($src);

			$text = "Unless someone like you cares a whole awful lot, nothing is going to get better. It's not.";

			$this->assertEquals($text, $file->getData(), "Returned file data does not equal original text.");
			$this->assertEquals($text, $file->compile(), "Returned compiled data does not equal original text.");

			$this->assertEquals($text, $alt->getData(), "Returned file data from constructor load does not equal original text.");
			$this->assertEquals($text, $alt->compile(), "Returned compiled data from constructor load does not equal original text.");

			unset($file);
		}

		/**
		 * Test file loading options/name caching.
		 */
		public function testNoAutoLoad() {
			$src = "tests/resources/test_text_file.txt";
			$file = new GenericFile($src, false);

			$this->assertEquals("", $file->getData(), "File data was loaded when we specified not to.");
		}

		/**
		 * Test writing some data to disk.
		 */
		public function testWriting() {
			$src = "GenericFileTest.Experiment.tmp";
			$data = "I like nonsense; it wakes up the brain cells.";

			$file = new GenericFile();
			$file->setData($data);
			$file->save($src, true);

			$this->assertFileExists($src, "File was not written to disk.");

			$file = new GenericFile(); // Fresh instance.
			$file->read($src);
			$this->assertEquals($data, $file->getData(), "Data from file did not match what we tried to write.");

			unset($file);
			unlink($src); // Clean-up after ourselves.
		}

		/**
		 * Test writing to a file without any data.
		 */
		public function testBlankWrite() {
			$src = "GenericFileTest.BlankExperiment.tmp";

			$file = new GenericFile();
			$file->save($src, true);

			$this->assertFileExists($src, "File was not written to disk.");

			$file = new GenericFile($src); // Fresh instance.
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

			$file = new GenericFile();
			$file->setData($data);
			$file->save($src); // Should not throw exception, since file does not exist.

			$overwrite = "You can steer yourself in any direction you choose.";

			try {
				// This data should not get saved to the disk.
				$file->setData($overwrite);

				$file->save($src, false);
				$this->fail("Attempt to overwrite a file without overwrite flag was not stopped by exception.");
			} catch (KrameWorkFileException $e) {
				// Expected, since we tried to overwrite without specifying.
			}

			$file = new GenericFile($src);

			$this->assertNotEquals($overwrite, $file->getData(), "File data was overwritten.");
			$this->assertEquals($data, $file->getData(), "File data does not match original or overwritten?");

			$final = "You're on your own, and you know what...";
			$file->setData($final);
			$file->save($src, true);

			$file = new GenericFile($src);
			$this->assertEquals($final, $file->getData(), "File did not get overwritten when we intended it to.");

			unlink($src);
		}

		/**
		 * Test the filename cached from read() calls works as expected.
		 */
		public function testSaveNameCache() {
			$src = "GenericFileTest.CacheTest.tmp";
			$data = "Maybe Christmas, the Grinch thought, doesn't come from a store.";

			// Save our initial data we can use to test with.
			$file = new GenericFile();
			$file->setData($data);
			$file->save($src, true);

			$newData = "Don't cry because it's over. Smile because it happened.";

			// Attempt to save without the file name.
			$file = new GenericFile($src);
			$file->setData($newData);
			$file->save();

			// Load the data gain to check it saved.
			$file = new GenericFile($src);
			$this->assertEquals($newData, $file->getData(), "File data does not match after saving without a file name.");

			unlink($src);
		}
	}