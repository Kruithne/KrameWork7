<?php
	use KrameWork\Timing\Timer;
	use KrameWork\Timing\Time;

	require_once(__DIR__ . "/../src/Timing/Timer.php");
	require_once(__DIR__ . "/../src/Timing/Time.php");

	class TimerTest extends \PHPUnit_Framework_TestCase
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

			usleep(10);

			$this->assertGreaterThanOrEqual(Time::MICROSECOND * 10, $timer->getElapsed());
		}

		/**
		 * Test that auto-initiated timers work as expected.
		 */
		public function testAutoInitiatedTimer() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, true);

			usleep(10);
			$this->assertGreaterThanOrEqual(Time::MICROSECOND * 10, $timer->getElapsed());

			unset($timer);
		}

		/**
		 * Test timers that are halted do not increment.
		 */
		public function testHaltedTimers() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$timer->start();

			usleep(10);
			$result = $timer->stop();
			$this->assertGreaterThanOrEqual(Time::MICROSECOND * 10, $result);

			usleep(20);
			$this->assertEquals($result, $timer->getElapsed());

			unset($timer);
		}

		/**
		 * Test that timers continue after getElapsed() is called without stopping.
		 */
		public function testContinuedTimers() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$timer->start();

			usleep(10);
			$result = $timer->getElapsed();

			usleep(20);
			$this->assertNotEquals($result, $timer->getElapsed());

			unset($timer);
		}

		/**
		 * Test restart functionality.
		 */
		public function testRestart() {
			$timer = new Timer(Timer::FORMAT_MICROSECONDS, false);
			$timer->start();

			usleep(1);
			$original = $timer->restart();
			$this->assertGreaterThanOrEqual(Time::MICROSECOND * 1, $original);

			usleep(2);
			$this->assertLessThan($original, $timer->getElapsed());

			unset($timer);
		}
	}