<?php
	namespace KrameWork\Data;

	/**
	 * Class DateValue
	 * A date value
	 */
	class DateValue extends Value
	{
		public function __construct($value)
		{
			parent::__construct(is_numeric($value) || $value === null ? $value : strtotime($value));
		}

		public function JSON()
		{
			// Use ISO 8601 format to support moment.js client side
			return date($this->value, 'c');
		}

		public function Compare($to)
		{
			$toValue = ($to instanceof DateValue) ? $to->Real() : 0;
			return $this->value - $toValue;
		}

		public function __toString()
		{
			return date('d.m.Y', $this->value);
		}
	}