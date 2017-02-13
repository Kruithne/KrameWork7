<?php
	namespace KrameWork\Data;

	/**
	 * Class DecimalValue
	 * A decimal value
	 */
	class DecimalValue extends Value
	{
		/**
		 * DecimalValue constructor.
		 * @param float|mixed $value Value to be cast to float and encapsulated
		 */
		public function __construct($value) {
			parent::__construct($value === null ? null : floatval($value));
		}

		/**
		 * Utility function for sorting a list of values
		 * @param $to Value|mixed A value to compare against
		 * @return int Sorting index
		 */
		public function compare($to) {
			$toValue = ($to instanceof Value) ? $to->Real() : floatval($to);
			return $this->value - $toValue;
		}

		/**
		 * @return string The encapsulated value as presented by a string
		 */
		public function __toString() {
			return number_format($this->value, 2, ',', ' ');
		}
	}