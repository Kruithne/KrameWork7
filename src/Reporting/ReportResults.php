<?php
	namespace KrameWork\Reporting;

	/**
	 * Class SQLReportResults
	 * @property string $hash
	 * @property array $data
	 */
	class ReportResults
	{
		public function __construct($data)
		{
			$this->data = $data;
			$this->hash = sha1(serialize($data));
		}

		public $hash;
		public $data;
	}