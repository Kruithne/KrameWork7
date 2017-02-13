<?php
	namespace KrameWork\Data;

	/**
	 * Class IntegerValue
	 * An integer value
	 */
	class IntegerValue extends Value
	{
		/**
		 * IntegerValue constructor.
		 * @param int|mixed $value A value to be cast to int and encapsulated
		 */
		public function __construct($value) {
			parent::__construct($value === null ? null : intval($value));
		}

		/**
		 * Utility function for sorting a list of values
		 * @param $to Value|mixed A value to compare against
		 * @return int Sorting index
		 */
		public function Compare($to) {
			$toValue = ($to instanceof Value) ? $to->Real() : intval($to);
			return $this->value - $toValue;
		}
	}