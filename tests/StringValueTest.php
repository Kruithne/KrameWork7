<?php
	use KrameWork\Data\StringValue;

	require_once(__DIR__ . "/../src/Data/StringValue.php");

	class StringValueTest extends \PHPUnit\Framework\TestCase
	{
		/**
		 * Verify that a value can be null
		 */
		public function testNullValue() {
			$value = new StringValue(null);
			$this->assertEquals(null, $value->real());
		}

		/**
		 * Verify that a value can be a string
		 */
		public function testStringValue() {
			$value = new StringValue("test");
			$this->assertEquals("test", $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new StringValue("test");
			$this->assertEquals("test", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new StringValue("test");
			$this->assertEquals("test", $value->json());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new StringValue("test 1");
			$this->assertLessThan(0, $value->compare("test 2"));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new StringValue("test 1");
			$this->assertEquals(0, $value->compare("test 1"));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new StringValue("test 2");
			$this->assertGreaterThan(0, $value->compare("test 1"));
		}

		public function testValueCompareNull() {
			$value = new StringValue('test');
			$this->assertGreaterThan(0, $value->compare(null));
			$this->assertGreaterThan(0, $value->compare(new StringValue(null)));
		}

		public function testNullCompareNull() {
			$value = new StringValue(null);
			$this->assertEquals(0, $value->compare(null));
			$this->assertEquals(0, $value->compare(new StringValue(null)));
		}
	}
