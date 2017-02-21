<?php
	use KrameWork\Utils\UUID;
	require_once(__DIR__ . '/../src/Utils/UUID.php');

	class UUIDTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test the functionality of UUID::isValid().
		 */
		public function testIsValid() {
			$this->assertTrue(UUID::isValid('6ba7b810-9dad-11d1-80b4-00c04fd430c8'));
			$this->assertFalse(UUID::isValid('thisIsNotValid'));
		}

		/**
		 * Test generation of v3 namespace based UUID.
		 */
		public function testV3Generation() {
			$valid = UUID::generate_v3(UUID::NAMESPACE_X500, 'bob');
			$equal = UUID::generate_v3(UUID::NAMESPACE_X500, 'bob');
			$diff_ns = UUID::generate_v3(UUID::NAMESPACE_DNS, 'bob');
			$diff_ky = UUID::generate_v3(UUID::NAMESPACE_X500, 'fred');
			$invalid = UUID::generate_v3('invalidNamespace', 'bob');

			// Validity checks.
			$this->assertTrue(UUID::isValid($valid)); // Valid UUID is valid.
			$this->assertTrue(UUID::isValid($equal)); // Equal UUID is valid.
			$this->assertTrue(UUID::isValid($diff_ns)); // Diff namespace UUID is valid.
			$this->assertTrue(UUID::isValid($diff_ky)); // Diff name UUID is valid.
			$this->assertEquals(UUID::NIL, $invalid); // Invalid UUID.

			// Equality checks.
			$this->assertEquals($valid, $equal); // valid == equal
			$this->assertNotEquals($valid, $diff_ns); // valid !== diff_ns
			$this->assertNotEquals($valid, $diff_ky); // valid !== diff_ky
		}

		/**
		 * Test generation of v5 namespace based UUID.
		 */
		public function testV5Generation() {
			$valid = UUID::generate_v5(UUID::NAMESPACE_X500, 'bob');
			$equal = UUID::generate_v5(UUID::NAMESPACE_X500, 'bob');
			$diff_ns = UUID::generate_v5(UUID::NAMESPACE_DNS, 'bob');
			$diff_ky = UUID::generate_v5(UUID::NAMESPACE_X500, 'fred');
			$invalid = UUID::generate_v5('invalidNamespace', 'bob');

			// Validity checks.
			$this->assertTrue(UUID::isValid($valid)); // Valid UUID is valid.
			$this->assertTrue(UUID::isValid($equal)); // Equal UUID is valid.
			$this->assertTrue(UUID::isValid($diff_ns)); // Diff namespace UUID is valid.
			$this->assertTrue(UUID::isValid($diff_ky)); // Diff name UUID is valid.
			$this->assertEquals(UUID::NIL, $invalid); // Invalid UUID.

			// Equality checks.
			$this->assertEquals($valid, $equal); // valid == equal
			$this->assertNotEquals($valid, $diff_ns); // valid !== diff_ns
			$this->assertNotEquals($valid, $diff_ky); // valid !== diff_ky
		}

		/**
		 * Test generation of pseudo-random v4 UUIDs.
		 */
		public function testV4Generation() {
			$stack = [];

			// Generate and check 10 UUIDs.
			for ($i = 1; $i < 10; $i++) {
				$uuid = UUID::generate_v4();
				$this->assertTrue(UUID::isValid($uuid)); // Validity.
				$this->assertFalse(array_key_exists($uuid, $stack)); // Uniqueness.
				$stack[$uuid] = true;
			}
		}
	}