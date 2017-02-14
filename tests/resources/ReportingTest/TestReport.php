<?php
	namespace resources\ReportingTest;

	require_once(__DIR__ . '/../../../src/Reporting/Report.php');

	use KrameWork\Caching\IDataCache;
	use KrameWork\Reporting\Report;
	use KrameWork\Reporting\ReportColumn;

	class TestReport extends Report
	{
		public function __construct(IDataCache $cache, $key, $cacheTTL = 300) {
			parent::__construct($cache, $key, $cacheTTL);
			$this->data = [];
		}

		/**
		 * @return ReportColumn[]
		 */
		public function columns() {
			return $this->columns;
		}

		protected function run() {
			return $this->data;
		}

		public $columns;
		public $data;
	}