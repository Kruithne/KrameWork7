<?php
	require_once(__DIR__ . "/../src/Storage/Directory.php");
	require_once(__DIR__ . "/../src/Utils/StringUtil.php");

	use KrameWork\Storage\Directory;
	use KrameWork\Utils\StringUtil;

	class DirectoryTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Test the basic functionality of the Directory class.
		 */
		public function testBasics() {
			$dir = new Directory(__DIR__. "/resources/DirectoryTest");
			$this->assertTrue($dir->exists(), "Directory does not exist when it should.");

			$dir = new Directory(__DIR__. "/resources/NonExistentDirectory");
			$this->assertFalse($dir->exists(), "Directory exists when it should not.");

			unset($dir);
		}

		/**
		 * Test basic directory listing works as expected.
		 */
		public function testDirectoryListing() {
			$dir = new Directory(__DIR__. "/resources/DirectoryTest");
			$items = $dir->getItems(Directory::INCLUDE_DIRECTORIES | Directory::INCLUDE_FILES);

			$expected = ["DirectoryA", "DirectoryB", "RandomFileA.txt", "RandomFileB.txt"];

			$this->assertEquals(count($items), count($expected), "Unexpected amount of items returned.");
			foreach ($expected as $item)
				$this->assertTrue(in_array($item, $items), $item . " missing from directory items.");

			unset($dir, $items, $expected);
		}

		/**
		 * Test the correct directory item wrappers are returned.
		 */
		public function testDirectoryListingWrappers() {
			$dir = new Directory(__DIR__. "/resources/DirectoryTest");
			$items = $dir->getItems(Directory::INCLUDE_DIRECTORIES | Directory::INCLUDE_FILES | Directory::USE_WRAPPERS);

			$expected = ["DirectoryA" => "Directory", "DirectoryB" => "Directory", "RandomFileA.txt" => "File", "RandomFileB.txt" => "File"];
			$this->assertEquals(count($items), count($expected), "Unexpected amount of items returned.");

			/**
			 * @var \KrameWork\Storage\DirectoryItem $item
			 */
			foreach ($items as $item) {
				$expectedNode = $expected[$item->getName()] ?? null;
				$this->assertNotNull($expectedNode, "Directory listing produced unexpected node.");
				$this->assertEquals($expectedNode, StringUtil::namespaceBase(get_class($item)), "Directory node was of unexpected type.");
			}

			unset($dir, $items, $expected);
		}

		/**
		 * Test that getFiles() returns expected results.
		 */
		public function testGetFiles() {
			$dir = new Directory(__DIR__ . "/resources/DirectoryTest");
			$items = $dir->getFiles(0);

			$expected = ["RandomFileA.txt", "RandomFileB.txt"];
			$this->assertEquals(count($expected), count($items), "Unexpected amount of files returned.");

			foreach ($expected as $item)
				$this->assertTrue(in_array($item, $items), $item . " missing from file listing.");

			unset($dir, $items, $expected);
		}

		/**
		 * Test that getDirectories() returns expected results.
		 */
		public function testGetDirectories() {
			$dir = new Directory(__DIR__ . "/resources/DirectoryTest");
			$items = $dir->getDirectories(0);

			$expected = ["DirectoryA", "DirectoryB"];
			$this->assertEquals(count($expected), count($items), "Unexpected amount of files returned.");

			foreach ($expected as $item)
				$this->assertTrue(in_array($item, $items), $item . " missing from directories listing.");

			unset($dir, $items, $expected);
		}

		/**
		 * Test hidden files are returned with Directory::INCLUDE_HIDDEN enabled.
		 */
		public function testHiddenFiles() {
			$dir = new Directory(__DIR__ . "/resources/DirectoryTest");
			$items = $dir->getFiles(Directory::INCLUDE_HIDDEN);

			$expected = ["RandomFileA.txt", "RandomFileB.txt", ".hiddenfile"];
			$this->assertEquals(count($expected), count($items), "Unexpected amount of files returned.");

			foreach ($expected as $item)
				$this->assertTrue(in_array($item, $items), $item . " missing from hidden files listing.");

			unset($dir, $items, $expected);
		}

		/**
		 * Test that full paths are returned correctly with Directory::RETURN_FULL_PATHS
		 */
		public function testFullPaths() {
			$path = StringUtil::formatDirectorySlashes(__DIR__ . "/resources/DirectoryTest");
			$dir = new Directory($path);
			$items = $dir->getFiles(Directory::RETURN_FULL_PATHS);

			$expected = [DIRECTORY_SEPARATOR . "RandomFileA.txt", DIRECTORY_SEPARATOR . "RandomFileB.txt"];
			$this->assertEquals(count($expected), count($items), "Unexpected amount of files returned.");

			foreach ($expected as $item)
				$this->assertTrue(in_array($path . $item, $items), $item . " full path missing from file listing.");


			unset($dir, $items, $expected);
		}

		/**
		 * Test the isValid() call works as expected.
		 */
		public function testIsValid() {
			// Existing directory (Expected: true)
			$dir = new Directory(__DIR__ . "/resources/DirectoryTest");
			$this->assertTrue($dir->isValid(), "Directory is not valid when it was expected to be.");

			// Non-existing directory (Expected: false)
			$dir = new Directory(__DIR__ . "/resources/SomewhereNonExistent");
			$this->assertFalse($dir->isValid(), "Invalid directory is considered valid.");

			// Existing non-directory (Expected: false)
			$dir = new Directory(__DIR__ . "/resources/test_json_file.json");
			$this->assertFalse($dir->isValid(), "Existing file considered valid directory.");

			unset($dir);
		}

		/**
		 * Test create() works as expected.
		 */
		public function testDirectoryCreation() {
			$path = __DIR__ . "/resources/DirectoryCreationTest";
			@rmdir($path); // Clean from broken tests.

			$dir = new Directory($path);
			$this->assertFalse($dir->exists(), "Test directory exists before we created it?");
			$dir->create();
			$this->assertTrue($dir->exists(), "Test directory does not exist after creation.");

			unset($dir);
			@rmdir($path);
		}

		/**
		 * Test directory creation using constructor parameter.
		 */
		public function testDirectoryCreationConstructor() {
			$path = __DIR__ . "/resources/DirectoryCreationTest";
			@rmdir($path); // Clean from broken tests.

			$this->assertFalse(file_exists($path), "Test directory exists before we created it?");
			$dir = new Directory($path, true);
			$this->assertTrue($dir->exists(), "Test directory does not exist after creation.");

			unset($dir);
			@rmdir($path);
		}

		/**
		 * Test the functionality of hasFile().
		 */
		public function testDirectoryHasFile() {
			$dir = new Directory(__DIR__ . "/resources/DirectoryTest");
			$this->assertTrue($dir->hasFile("RandomFileA.txt"), "Directory does not contain RandomFileA.txt");
			$this->assertFalse($dir->hasFile("NonExistentFile.dat"), "Directory contains missing file.");
			$this->assertFalse($dir->hasFile("DirectoryA"), "Directory existence returned in hasFile()");
			unset($dir);
		}

		/**
		 * Test the functionality of hasDirectory().
		 */
		public function testDirectoryHasDirectory() {
			$dir = new Directory(__DIR__ . "/resources/DirectoryTest");
			$this->assertTrue($dir->hasDirectory("DirectoryA"), "Directory does not contain DirectoryA.");
			$this->assertFalse($dir->hasDirectory("NonExistentDirectory"), "Directory contains missing directory.");
			$this->assertFalse($dir->hasDirectory("RandomFileA.txt"), "File existence returned in hasDirectory()");
			unset($dir);
		}

		/**
		 * Test that delete() works as expected.
		 */
		public function testDirectoryDeletion() {
			$dir = new Directory(__DIR__ . "/resources/DirectoryDeleteTest", true);
			$this->assertTrue($dir->exists(), "File does not exist before deletion?");
			$dir->delete(false);
			$this->assertFalse($dir->exists(), "File exists after deletion.");

			unset($dir);
		}

		/**
		 * Test the functionality of createFile().
		 */
		public function testDirectoryCreateFile() {
			$dir = new Directory(__DIR__ . "/resources");
			$file = $dir->createFile("TestCreatedFile.tmp");

			$this->assertInstanceOf("\\KrameWork\\Storage\\File", $file, "Returned object was not a wrapped file.");
			$this->assertTrue($file->exists(), "Created file does not exist?");

			$file->delete();
			unset($dir, $file);
		}

		public function testDirectoryCreateDirectory() {
			$dir = new Directory(__DIR__ . "/resources");
			$sub = $dir->createDirectory("TestCreatedDir");

			$this->assertInstanceOf("\\KrameWork\\Storage\\Directory", $sub, "Returned object was not a wrapped directory.");
			$this->assertTrue($sub->exists(), "Created directory does not exist?");

			$sub->delete(false);
			unset($dir, $sub);
		}
		/**
		 * Test recursive directory deletion works as expected.
		 */
		public function testRecursiveDirectoryDeletion() {
			// Clean-up from broken tests.
			@rmdir(__DIR__ . "/resources/DirectoryRecursiveDeletionTest/SubA");
			@unlink(__DIR__ . "/resources/DirectoryRecursiveDeletionTest/SubB/TestFile.txt");
			@rmdir(__DIR__ . "/resources/DirectoryRecursiveDeletionTest/SubB");
			@rmdir(__DIR__ . "/resources/DirectoryRecursiveDeletionTest");

			$dir = new Directory(__DIR__ . "/resources/DirectoryRecursiveDeletionTest", true);
			$this->assertTrue($dir->exists(), "Directory does not exist before deletion?");

			// Generate some recursive data to delete.
			$dir->createDirectory("SubA");
			$sub = $dir->createDirectory("SubB");
			$sub->createFile("TestFile.txt");

			$dir->delete(true);
			$this->assertFalse($dir->exists(), "Directory was not deleted.");
			unset($dir);
		}
	}