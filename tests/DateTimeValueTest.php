<?php
	use KrameWork\Data\DateTimeValue;

	require_once(__DIR__ . "/../src/Data/DateTimeValue.php");

	class DateTimeValueTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Verify that a value can be null
		 */
		public function testNullValue() {
			$value = new DateTimeValue(null);
			$this->assertEquals(null, $value->real());
		}

		/**
		 * Verify that a value can be a string
		 */
		public function testStringValue() {
			$value = new DateTimeValue("1980-12-19 8:42");
			$this->assertEquals(strtotime("1980-12-19 8:42"), $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new DateTimeValue(0);
			$this->assertEquals("01.01.1970 00:00", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new DateTimeValue(0);
			$this->assertEquals(date('c',0), $value->JSON());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new DateTimeValue(1);
			$this->assertSmallerThan(0, $value->compare(2));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new DateTimeValue(1);
			$this->assertEquals(0, $value->compare(1));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new DateTimeValue(2);
			$this->assertGreaterThan(0, $value->compare(1));
		}
	}
