<?php
	namespace KrameWork\Timing;

	/**
	 * Class Timer
	 * Cute little tiny timer tool named Tim.
	 *
	 * @package KrameWork\Timing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class Timer
	{
		const FORMAT_SECONDS = 0x1;
		const FORMAT_MICROSECONDS = 0x2;

		/**
		 * Timer constructor.
		 *
		 * @api
		 * @param int $format Timing format.
		 * @param bool $autoStart Timer will start on construction.
		 */
		public function __construct(int $format = self::FORMAT_SECONDS, bool $autoStart = false) {
			$this->format = $format;
			if ($autoStart)
				$this->start();
		}

		/**
		 * Start this timer.
		 *
		 * @api
		 */
		public function start() {
			$this->startTime = $this->getCurrentTime();
			$this->stopTime = null;
		}

		/**
		 * Stop the timer and return the current elapsed time.
		 *
		 * @api
		 */
		public function stop() {
			$this->stopTime = $this->getCurrentTime();
			return $this->getElapsed();
		}

		/**
		 * Restart the timer and return the current elapsed time.
		 *
		 * @api
		 */
		public function restart() {
			$stop = $this->stop();
			$this->start();
			return $stop;
		}

		/**
		 * Get the elapsed time of this timer.
		 *
		 * @api
		 * @return float|int
		 */
		public function getElapsed() {
			if ($this->startTime == null)
				return 0;

			return ($this->stopTime ?? $this->getCurrentTime()) - $this->startTime;
		}

		/**
		 * Get the current timestamp in the timers format.
		 *
		 * @internal
		 * @return int|float
		 */
		private function getCurrentTime() {
			if ($this->format == self::FORMAT_MICROSECONDS)
				return microtime(true);

			return time(); // Seconds (or default).
		}

		/**
		 * @var int
		 */
		private $format;

		/**
		 * @var int|float
		 */
		private $startTime;

		/**
		 * @var int|float
		 */
		private $stopTime;
	}