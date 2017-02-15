<?php
	use KrameWork\Caching\FileCache;
	require_once(__DIR__ . '/../src/Caching/FileCache.php');

	class FileCacheTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Ensure basic get/set operations work as expected.
		 */
		public function testValueStorage() {
			$cache = new FileCache('file_cache_test.dat', false);
			$cache->foo = 'bar';

			$this->assertEquals('bar', $cache->foo);
			unset($cache);
		}

		/**
		 * Test cache persistence.
		 */
		public function testPersistence() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);
			$cache->foo = 42;
			$cache->persist();

			$cache = new FileCache('file_cache_test.dat', false);
			$this->assertEquals(42, $cache->foo);

			unset($cache);
			@unlink('file_cache_test.dat'); // Clean-up.
		}

		/**
		 * Test removing a value from the cache.
		 */
		public function testValueRemoval() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);

			$cache->foo = 42;
			$this->assertEquals(42, $cache->foo);

			unset($cache->foo);
			$this->assertNull($cache->foo);

			unset($cache);
		}

		/**
		 * Test cache flushing operation.
		 */
		public function testCacheFlush() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);
			$cache->foo = 1;
			$cache->bar = 2;

			$this->assertEquals(1, $cache->foo);
			$this->assertEquals(2, $cache->bar);

			$cache->flush();

			$this->assertNull($cache->foo);
			$this->assertNull($cache->bar);

			unset($cache);
		}

		/**
		 * Test value incrementing.
		 */
		public function testValueIncrement() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);

			// Undefined value, default weight.
			$cache->increment('foo');
			$this->assertEquals(1, $cache->foo);

			// Undefined value, custom weight.
			$cache->increment('bar', 7);
			$this->assertEquals(7, $cache->bar);

			// Defined value, default weight.
			$cache->bar = 10;
			$cache->increment('bar');
			$this->assertEquals(11, $cache->bar);

			// Defined value, custom weight.
			$cache->foo = 10;
			$cache->increment('foo', 10);
			$this->assertEquals(20, $cache->foo);

			unset($cache);
		}

		/**
		 * Test value decrementing.
		 */
		public function testValueDecrement() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);

			// Undefined value, default weight.
			$cache->decrement('foo');
			$this->assertEquals(-1, $cache->foo);

			// Undefined value, custom weight.
			$cache->decrement('bar', 2);
			$this->assertEquals(-2, $cache->bar);

			// Defined value, default weight.
			$cache->foo = 3.4;
			$cache->decrement('foo');
			$this->assertEquals(2.4, $cache->foo);

			// Defined value, custom weight.
			$cache->bar = 500;
			$cache->decrement('bar', 200);
			$this->assertEquals(300, $cache->bar);

			unset($cache);
		}

		/**
		 * Test the exists() functionality of the cache.
		 */
		public function testExists() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);

			$cache->foo = 'false';
			$cache->bar = 0;

			$this->assertTrue($cache->exists('foo'));
			$this->assertTrue($cache->exists('bar'));

			unset($cache);
		}

		/**
		 * Test that values expire as expected.
		 */
		public function testValueExpire() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);

			$cache->store('foo', 'bar', 3);
			$this->assertEquals('bar', $cache->foo);

			sleep(4);
			$this->assertNull($cache->foo);
			unset($cache);
		}

		/**
		 * Test expiry persistence.
		 */
		public function testValueExpiryPersistence() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', true);

			// Store our two testing values.
			$cache->store('foo', 42, 8); // Expire in 7 seconds.
			$cache->store('bar', 0, 3); // Expire in 3 seconds.
			$cache->store('perm', true); // Never expire.

			// Check our values stored correctly.
			$this->assertEquals(42, $cache->foo);
			$this->assertEquals(0, $cache->bar);
			$this->assertEquals(true, $cache->perm);

			sleep(4); // Sleep 4 seconds to let 'bar' expire.

			$this->assertNull($cache->bar); // 'bar' should have expired by now.
			$this->assertEquals(42, $cache->foo); // 'foo' should not have.
			$this->assertEquals(true, $cache->perm); // Eternal!

			$cache = new FileCache('file_cache_test.dat', true); // Load new cache instance.
			$this->assertNull($cache->bar); // 'bar' should still be expired.
			$this->assertEquals(42, $cache->foo); // 'foo' should not have.
			$this->assertEquals(true, $cache->perm); // Eternal!

			sleep(5); // Sleep an additional 5 seconds to let 'foo' expire.
			$this->assertNull($cache->bar); // 'bar' should still not exist.
			$this->assertNull($cache->foo); // 'foo' should have expired by this point.
			$this->assertEquals(true, $cache->perm); // ETERNAL!

			unset($cache);
			unlink('file_cache_test.dat');
		}


		/**
		 * Test auto value clean-up on construction.
		 */
		public function testExpiryAutoCleanup() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', true);
			$cache->foo = 42; // Never expire.
			$cache->store('bar', 0, 3); // Expire in 3 seconds.
			unset($cache);

			sleep(4);

			$cache = new FileCache('file_cache_test.dat', true, true);
			$this->assertNull($cache->bar);
			$this->assertEquals(42, $cache->foo);

			unset($cache);
			unlink('file_cache_test.dat');
		}

		/**
		 * Test that expiry via timestamp works as expected.
		 */
		public function testExpiryByTimestamp() {
			@unlink('file_cache_test.dat'); // Delete from broken tests.
			$cache = new FileCache('file_cache_test.dat', false);

			$cache->store('foo', 'bar', time() + 3);
			$this->assertEquals('bar', $cache->foo);

			sleep(4);
			$this->assertNull($cache->foo);

			unset($cache);
		}
	}