<?php
	use KrameWork\Data\CurrencyValue;

	require_once(__DIR__ . "/../src/Data/CurrencyValue.php");

	class CurrencyValueTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Verify that a value can be null
		 */
		public function testNullValue() {
			$value = new CurrencyValue(null);
			$this->assertEquals(null, $value->real());
		}

		/**
		 * Verify that a value can be a string
		 */
		public function testStringValue() {
			$value = new CurrencyValue("4.2");
			$this->assertEquals(4.2, $value->real());
		}

		/**
		 * Verify that string conversion works
		 */
		public function testValueToString() {
			$value = new CurrencyValue(4.2);
			$this->assertEquals("4,20", (string)$value);
		}

		/**
		 * Verify that the JSON method works
		 */
		public function testValueToJSON() {
			$value = new CurrencyValue(4.2);
			$this->assertEquals(4.2, $value->JSON());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new CurrencyValue(1.5);
			$this->assertLessThan(0, $value->compare(2.5));
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareEqual() {
			$value = new CurrencyValue(1.5);
			$this->assertEquals(0, $value->compare(1.5));
		}

		/**
		 * Check that the compare function works for a > b
		 */
		public function testValueCompareBigger() {
			$value = new CurrencyValue(2.5);
			$this->assertGreaterThan(0, $value->compare(1.5));
		}
	}
