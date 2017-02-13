<?php
	use KrameWork\Data\IntegerValue;

	require_once(__DIR__ . "/../src/Data/IntegerValue.php");

	class IntegerValueTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Verify that a value can be null
		 */
		public function testNullValue() {
			$value = new IntegerValue(null);
			$this->assertEquals(null, $value->real());
		}

		/**
		 * Verify that a value can be a string
		 */
		public function testStringValue() {
			$value = new IntegerValue("42");
			$this->assertEquals(42, $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new IntegerValue(42);
			$this->assertEquals("42", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new IntegerValue(42);
			$this->assertEquals(42, $value->JSON());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new IntegerValue(1);
			$this->assertSmallerThan(0, $value->compare(2));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new IntegerValue(1);
			$this->assertEquals(0, $value->compare(1));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new IntegerValue(2);
			$this->assertGreaterThan(0, $value->compare(1));
		}
	}
