<?php
	/**
	 * Created by PhpStorm.
	 * User: MNI
	 * Date: 12.02.2017
	 * Time: 22.07
	 */
	namespace KrameWork\Data;

	/**
	 * Class IntegerValue
	 * An integer value
	 */
	class IntegerValue extends Value
	{
		public function __construct($value)
		{
			parent::__construct(intval($value));
		}

		public function Compare($to)
		{
			$toValue = ($to instanceof Value) ? $to->Real() : intval($to);
			return $this->value - $toValue;
		}
	}