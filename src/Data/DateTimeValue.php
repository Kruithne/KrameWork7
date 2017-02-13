<?php
	namespace KrameWork\Data;

	/**
	 * Class DateTimeValue
	 * A date and time value
	 */
	class DateTimeValue extends DateValue
	{
		/**
		 * @return string The date and time in "31.12.2016 12:38" format
		 */
		public function __toString() {
			return $this->value ? date('d.m.Y H:i', $this->value) : '';
		}
	}