<?php
	use KrameWork\Data\DecimalValue;

	require_once(__DIR__ . "/../src/Data/DecimalValue.php");

	class DecimalValueTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Verify that a value can be null
		 */
		public function testNullValue() {
			$value = new DecimalValue(null);
			$this->assertEquals(null, $value->real());
		}

		/**
		 * Verify that a value can be a string
		 */
		public function testStringValue() {
			$value = new DecimalValue("4.2");
			$this->assertEquals(4.2, $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new DecimalValue(4.2);
			$this->assertEquals("4.2", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new DecimalValue(4.2);
			$this->assertEquals(4.2, $value->JSON());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new DecimalValue(1.5);
			$this->assertSmallerThan(0, $value->compare(2.5));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new DecimalValue(1.5);
			$this->assertEquals(0, $value->compare(1.5));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new DecimalValue(2.5);
			$this->assertGreaterThan(0, $value->compare(1.5));
		}
	}
