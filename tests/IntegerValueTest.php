<?php
	use KrameWork\Data\IntegerValue;

	require_once(__DIR__ . "/../src/Data/IntegerValue.php");

	class IntegerValueTest extends \PHPUnit\Framework\TestCase
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
			$this->assertEquals(42, $value->json());
		}

		/**
		 * Check that the compare function works for a < b
		 */
		public function testValueCompareSmaller() {
			$value = new IntegerValue(1);
			$this->assertLessThan(0, $value->compare(2));
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


		public function testValueCompareNull() {
			for ($i = -1; $i < 2; ++$i) {
				$value = new IntegerValue($i);
				$this->assertGreaterThan(0, $value->compare(null));
				$this->assertGreaterThan(0, $value->compare(new IntegerValue(null)));
			}
		}

		public function testNullCompareNull() {
			$value = new IntegerValue(null);
			$this->assertEquals(0, $value->compare(null));
			$this->assertEquals(0, $value->compare(new IntegerValue(null)));
		}
	}
