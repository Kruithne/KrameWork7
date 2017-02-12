<?php
	namespace KrameWork\Reporting;
	use KrameWork\Data\Value;

	/**
	 * Class ResultRow
	 * Encapsulates a result row of formatted data, with support for JSON serialization
	 */
	class ReportRow implements \JsonSerializable
	{
		public function __construct($data)
		{
			$this->data = $data;
		}

		/**
		 * Specify data which should be serialized to JSON
		 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
		 * @return mixed data which can be serialized by <b>json_encode</b>,
		 * which is a value of any type other than a resource.
		 * @since 5.4.0
		 */
		function jsonSerialize()
		{
			$serialize = [];
			foreach ($this->data as $field => $data)
				if ($data instanceof Value)
					$serialize[$field] = $data->JSON();
				else
					$serialize[$field] = $data;

			return $serialize;
		}

		private $data;
	}