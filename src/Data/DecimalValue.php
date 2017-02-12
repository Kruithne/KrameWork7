<?php
	/**
	 * Created by PhpStorm.
	 * User: MNI
	 * Date: 12.02.2017
	 * Time: 22.07
	 */
	namespace KrameWork\Data;

	/**
	 * Class DecimalValue
	 * A decimal value
	 */
	class DecimalValue extends Value
	{
		public function __construct($value)
		{
			parent::__construct(floatval($value));
		}

		public function Compare($to)
		{
			$toValue = ($to instanceof Value) ? $to->Real() : floatval($to);
			return $this->value - $toValue;
		}

		public function __toString()
		{
			return number_format($this->value, 2, ',', ' ');
		}
	}