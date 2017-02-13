<?php
	use KrameWork\Data\DateValue;

	require_once(__DIR__ . "/../src/Data/DateValue.php");

	class DateValueTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Verify that a value can be null
		 */
		public function testNullValue() {
			$value = new DateValue(null);
			$this->assertEquals(null, $value->real());
		}

		/**
		 * Verify that a value can be a string
		 */
		public function testStringValue() {
			$value = new DateValue("1980-12-19 8:42");
			$this->assertEquals(strtotime("1980-12-19 8:42"), $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new DateValue(0);
			$this->assertEquals("01.01.1970", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new DateValue('1980-01-01');
			$this->assertEquals('1980-01-01T00:00:00+00:00', $value->JSON());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new DateValue('1980-01-01');
			$this->assertLessThan(0, $value->compare(new DateValue('1981-01-01')));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new DateValue('1980-01-01');
			$this->assertEquals(0, $value->compare(new DateValue('1980-01-01')));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new DateValue('1981-01-01');
			$this->assertGreaterThan(0, $value->compare(new DateValue('1980-01-01')));
		}

		public function testValueCompareNull() {
			$value = new DateValue('1980-01-01');
			$this->assertGreaterThan(0, $value->compare(null));
			$this->assertGreaterThan(0, $value->compare(new DateValue(null)));
		}

		public function testNullCompareNull() {
			$value = new DateValue(null);
			$this->assertEquals(0, $value->compare(null));
			$this->assertEquals(0, $value->compare(new DateValue(null)));
		}
	}
