<?php
	namespace KrameWork\Data;

	/**
	 * Class DateValue
	 * A date value
	 */
	class DateValue extends Value
	{
		/**
		 * DateValue constructor.
		 * @param string|int $value String to be converted to a timestamp, or a unix timestamp
		 */
		public function __construct($value) {
			parent::__construct(is_numeric($value) || $value === null ? $value : strtotime($value));
		}

		/**
		 * Convert the timestamp into a format suitable for exporting in JSON
		 * @return null|string The timestamp formatted according to ISO 8601, or null
		 */
		public function JSON() {
			// Use ISO 8601 format to support moment.js client side
			return $this->value ? date($this->value, 'c') : null;
		}

		/**
		 * Utility function for sorting a list of values
		 * @param $to Value|mixed A value to compare against
		 * @return int Sorting index
		 */
		public function compare($to) {
			$toValue = ($to instanceof DateValue) ? $to->Real() : 0;
			return $this->value - $toValue;
		}

		/**
		 * @return string The encapsulated value as presented by a string
		 */
		public function __toString() {
			return date('d.m.Y', $this->value);
		}
	}