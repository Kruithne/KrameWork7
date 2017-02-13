<?php
	namespace KrameWork\Data;

	/**
	 * Class Value
	 * A generic value
	 */
	class Value
	{
		/**
		 * Value constructor.
		 * @param $value mixed The encapsulated value
		 */
		public function __construct($value) {
			$this->value = $value;
		}

		/**
		 * @return mixed The encapsulated value
		 */
		public function Real() {
			return $this->value;
		}

		/**
		 * @return mixed The value to be encoded into JSON
		 */
		public function JSON() {
			return $this->value;
		}

		/**
		 * Utility function for sorting a list of values
		 * @param $to Value|mixed A value to compare against
		 * @return int Sorting index
		 */
		public function Compare($to) {
			return strnatcasecmp((string)$this->value, (string)$to);
		}

		/**
		 * @return string The encapsulated value as presented by a string
		 */
		public function __toString() {
			return (string)$this->value;
		}

		/**
		 * @var mixed The encapsulated value
		 */
		protected $value;
	}