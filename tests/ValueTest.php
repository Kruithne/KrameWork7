<?php
	use KrameWork\Data\Value;

	require_once(__DIR__ . "/../src/Data/Value.php");

	class ValueTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Verify that a value can be null
		 */
		public function testNullValue() {
			$value = new Value(null);
			$this->assertEquals(null, $value->real());
		}

		/**
		 * Verify that a value can be a boolean
		 */
		public function testBooleanValue() {
			$value = new Value(true);
			$this->assertEquals(true, $value->real());
		}

		/**
		 * Verify that a value can be a string
		 */
		public function testStringValue() {
			$value = new Value("test");
			$this->assertEquals("test", $value->real());
		}

		/**
		 * Verify that a value can be an integer
		 */
		public function testIntegerValue() {
			$value = new Value(42);
			$this->assertEquals(42, $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new Value(42);
			$this->assertEquals("42", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new Value(42);
			$this->assertEquals(42, $value->JSON());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new Value(1);
			$this->assertSmallerThan(0, $value->compare(2));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new Value(1);
			$this->assertEquals(0, $value->compare(1));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new Value(2);
			$this->assertGreaterThan(0, $value->compare(1));
		}
	}
