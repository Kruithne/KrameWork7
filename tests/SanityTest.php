<?php
	class SanityTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Check if the boolean value of true, is equal to itself.
		 */
		public function testSanity() {
			// If this fails, abandon all hope.
			$this->assertEquals(true, true, "The world as we once knew it, has ended.");
		}
	}