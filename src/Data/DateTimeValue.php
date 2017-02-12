<?php
	/**
	 * Created by PhpStorm.
	 * User: MNI
	 * Date: 12.02.2017
	 * Time: 22.08
	 */
	namespace KrameWork\Data;

	/**
	 * Class DateTimeValue
	 * A date and time value
	 */
	class DateTimeValue extends DateValue
	{
		public function __toString()
		{
			return date('d.m.Y H:i', $this->value);
		}
	}