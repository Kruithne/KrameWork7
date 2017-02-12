<?php
	/**
	 * Created by PhpStorm.
	 * User: MNI
	 * Date: 12.02.2017
	 * Time: 22.07
	 */
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