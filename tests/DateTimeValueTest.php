<?php
	use KrameWork\Data\DateTimeValue;

	require_once(__DIR__ . "/../src/Data/DateTimeValue.php");

	class DateTimeValueTest extends PHPUnit\Framework\TestCase
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
			$value = new DateTimeValue('1980-12-19 8:42');
			$this->assertEquals(strtotime('1980-12-19 8:42'), $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new DateTimeValue('1980-01-01');
			$this->assertEquals("01.01.1980 00:00", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new DateTimeValue('1980-01-01');
			$this->assertEquals('1980-01-01T00:00:00+00:00', $value->json());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new DateTimeValue('1980-01-01');
			$this->assertLessThan(0, $value->compare(new DateTimeValue('1981-01-01')));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new DateTimeValue('1980-01-01');
			$this->assertEquals(0, $value->compare(new DateTimeValue('1980-01-01')));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new DateTimeValue('1981-01-01');
			$this->assertGreaterThan(0, $value->compare(new DateTimeValue('1980-01-01')));
		}


		public function testValueCompareNull() {
			$value = new DateTimeValue('1980-01-01');
			$this->assertGreaterThan(0, $value->compare(null));
			$this->assertGreaterThan(0, $value->compare(new DateTimeValue(null)));
		}

		public function testNullCompareNull() {
			$value = new DateTimeValue(null);
			$this->assertEquals(0, $value->compare(null));
			$this->assertEquals(0, $value->compare(new DateTimeValue(null)));
		}
	}
