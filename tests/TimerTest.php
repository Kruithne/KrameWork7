<?php
	use KrameWork\Timing\Timer;
	use KrameWork\Timing\Time;

	require_once(__DIR__ . "/../src/Timing/Timer.php");
	require_once(__DIR__ . "/../src/Timing/Time.php");

	class TimerTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test the results of a non-initiated timer.
		 */
		public function testNonInitiatedTimer() {
			$timer = new Timer(Timer::FORMAT_SECONDS, false);
			$this->assertEquals(0, $timer->getElapsed());

			$timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$this->assertEquals(0, $timer->getElapsed());

			unset($timer);
		}

		/**
		 * Test the results of an initiated timer.
		 */
		public function testInitiatedTimer() {
			$sTimer = new Timer(Timer::FORMAT_SECONDS, false);
			$mTimer = new Timer(Timer::FORMAT_MICROSECONDS, false);

			$sTimer->start();
			$mTimer->start();

			sleep(1);
			$this->assertGreaterThanOrEqual(1, $sTimer->getElapsed());
			$this->assertGreaterThanOrEqual(1, $mTimer->getElapsed());

			unset($sTimer);
			unset($mTimer);
		}

		/**
		 * Test microsecond precision works as expected.
		 */
		public function testMicroTimer() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$timer->start();

			sleep(1);

			$this->assertGreaterThanOrEqual(1, $timer->getElapsed());
		}

		/**
		 * Test that auto-initiated timers work as expected.
		 */
		public function testAutoInitiatedTimer() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, true);

			sleep(1);
			$this->assertGreaterThanOrEqual(1, $timer->getElapsed());

			unset($timer);
		}

		/**
		 * Test timers that are halted do not increment.
		 */
		public function testHaltedTimers() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$timer->start();

			sleep(1);
			$result = $timer->stop();
			$this->assertGreaterThanOrEqual(1, $result);

			sleep(1);
			$this->assertEquals($result, $timer->getElapsed());

			unset($timer);
		}

		/**
		 * Test that timers continue after getElapsed() is called without stopping.
		 */
		public function testContinuedTimers() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$timer->start();

			sleep(1);
			$result = $timer->getElapsed();

			sleep(1);
			$this->assertNotEquals($result, $timer->getElapsed());

			unset($timer);
		}

		/**
		 * Test restart functionality.
		 */
		public function testRestart() {
			$timer = new Timer(Timer::FORMAT_SECONDS, false);
			$timer->start();

			sleep(1);
			$original = $timer->restart();
			$this->assertGreaterThanOrEqual(Time::SECOND * 1, $original);

			sleep(2);
			$this->assertTrue($original < $timer->getElapsed());

			unset($timer);
		}
	}