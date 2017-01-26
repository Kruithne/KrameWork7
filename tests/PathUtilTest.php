<?php
	namespace KrameWork\Utils;

	require_once("src/Utils/PathUtil.php");

	class PathUtilTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test the functionality of PathUtil::formatSlashes
		 */
		public function testFormatSlashes() {
			// Set-up test string.
			$sep = DIRECTORY_SEPARATOR == "/" ? "\\" : "/";
			$parts = ["The", "dog", "pooped", "on", "the", "lawn"];
			$str = join($sep, $parts);

			$cleaned = PathUtil::formatSlashes($str);
			$this->assertNotContains($sep, $cleaned, "Cleaned path still contains invalid slashes.");
		}
	}