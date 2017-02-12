<?php
	namespace KrameWork\Data;

	/**
	 * Class IntegerValue
	 * An integer value
	 */
	class IntegerValue extends Value
	{
		public function __construct($value)
		{
			parent::__construct($value === null ? null : intval($value));
		}

		public function Compare($to)
		{
			$toValue = ($to instanceof Value) ? $to->Real() : intval($to);
			return $this->value - $toValue;
		}
	}