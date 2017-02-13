<?php
	namespace KrameWork\Reporting;

	use KrameWork\Data\Value;

	/**
	 * Class ResultRow
	 * Encapsulates a result row of formatted data, with support for JSON serialization
	 */
	class ReportRow implements \JsonSerializable
	{
		/**
		 * ReportRow constructor.
		 * @param $data array|Value[] The underlying result row
		 */
		public function __construct($data) {
			$this->data = $data;
		}

		function jsonSerialize() {
			$serialize = [];
			foreach ($this->data as $field => $data)
				if ($data instanceof Value)
					$serialize[$field] = $data->JSON();
				else
					$serialize[$field] = $data;

			return $serialize;
		}

		/**
		 * @var array|Value[] The data contained in the row
		 */
		private $data;
	}