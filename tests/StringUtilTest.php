<?php
	namespace KrameWork\Utils;

	require_once("src/Utils/StringUtil.php");

	class StringUtilTest extends \PHPUnit_Framework_TestCase {
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
	}