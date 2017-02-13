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

		public function Compare($to) {
			$toValue = ($to instanceof Value) ? $to->Real() : floatval($to);
			return $this->value - $toValue;
		}

		public function __toString() {
			return number_format($this->value, 2, ',', ' ');
		}
	}