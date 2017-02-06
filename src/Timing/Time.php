<?php
	namespace KrameWork\Timing;

	/**
	 * Class Time
	 * Static constants of time.
	 *
	 * @package KrameWork\Timing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class Time
	{
		// Time values are all in seconds.
		const MICROSECOND	= 0.00001;
		const MILLISECOND 	= 0.001;

		const SECOND 		= 1;
		const MINUTE 		= self::SECOND * 60;
		const HOUR 			= self::MINUTE * 60;
		const DAY			= self::HOUR * 24;
		const WEEK 			= self::DAY * 7;
		const MONTH 		= self::WEEK * 4;
		const YEAR 			= self::MONTH * 12;
		const DECADE 		= self::YEAR * 10;
	}