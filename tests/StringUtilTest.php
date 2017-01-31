<?php
	require_once(__DIR__ . "/../src/Utils/StringUtil.php");

	use Kramework\Utils\StringUtil;

	class StringUtilTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Test StringUtil::startsWith() function.
		 */
		public function testStartsWith() {
			$data = "A short dog farted.";
			$this->assertTrue(StringUtil::startsWith($data, "A short "));
			$this->assertFalse(StringUtil::startsWith($data, "A hairy "));
		}

		public function testEndsWith() {
			$data = "A fat dog jumped.";
			$this->assertTrue(StringUtil::endsWith($data, " jumped."));
			$this->assertFalse(StringUtil::endsWith($data, " ran away."));
		}

		/**
		 * Test the functionality of StringUtil::formatSlashes
		 */
		public function testFormatSlashes() {
			// Set-up test string.
			$sep = DIRECTORY_SEPARATOR == "/" ? "\\" : "/";
			$parts = ["The", "dog", "pooped", "on", "the", "lawn"];
			$str = join($sep, $parts);

			$cleaned = StringUtil::formatDirectorySlashes($str);
			$this->assertNotContains($sep, $cleaned, "Cleaned path still contains invalid slashes.");
		}
	}