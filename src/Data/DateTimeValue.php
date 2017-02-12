<?php
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