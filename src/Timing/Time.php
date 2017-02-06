<?php
	/*
	 * Copyright (c) 2017 Kruithne (kruithne@gmail.com)
	 * https://github.com/Kruithne/KrameWork7
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

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