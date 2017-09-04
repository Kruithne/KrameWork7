<?php
	require_once 'PHPUnit/Autoload.php';

	use KrameWork\Caching\APCu;
	require_once(__DIR__ . '/../src/Caching/APCu.php');

	class APCuTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test that the environment has APCu installed and enabled.
		 */
		public function testEnabled() {
			$this->assertTrue(function_exists('apcu_cache_info'), "APCu is not installed/enabled?");
		}

		/**
		 * Test that basic value storage/retrieval works as expected.
		 */
		public function testValueStore() {
			$cache = new APCu();

			$cache->foo = 'bar';
			$cache->store('bar', 'foo');

			$this->assertEquals('bar', $cache->foo);
			$this->assertEquals('foo', $cache->bar);

			unset($cache);
		}

		/**
		 * Test value unsetting works as expected.
		 */
		public function testValueDelete() {
			$cache = new APCu();

			// Previously added value should still exist.
			$this->assertEquals('bar', $cache->foo);

			unset($cache->foo);
			$this->assertNull($cache->foo);

			unset($cache);
		}

		/**
		 * Test cache flushing works as expected.
		 */
		public function testCacheFlush() {
			$cache = new APCu();

			// Insert some data and ensure it exists.
			$cache->foo = 'bar';
			$cache->bar = 'foo';
			$this->assertEquals('bar', $cache->foo);
			$this->assertEquals('foo', $cache->bar);

			$cache->flush(); // Flush all cache data.
			$this->assertNull($cache->foo);
			$this->assertNull($cache->bar);

			unset($cache);
		}

		/**
		 * Test cache value incrementing.
		 */
		public function testValueIncrement() {
			$cache = new APCu();

			$cache->increment('foo');
			$this->assertEquals(1, $cache->foo);

			$cache->increment('foo', 5);
			$this->assertEquals(6, $cache->foo);

			$cache->flush();
			unset($cache);
		}

		/**
		 * Test cache value decrementing.
		 */
		public function testValueDecrement() {
			$cache = new APCu();

			$cache->decrement('bar');
			$this->assertEquals(-1, $cache->bar);

			$cache->decrement('bar', 7);
			$this->assertEquals(-8, $cache->bar);

			$cache->flush();
			unset($cache);
		}
	}