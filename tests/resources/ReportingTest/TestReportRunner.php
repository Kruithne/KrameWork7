<?php
	use KrameWork\Caching\IDataCache;
	use KrameWork\Reporting\ReportColumn;

	require_once(__DIR__ . '/../../../src/Reporting/ReportRunner.php');

	class TestReportRunner extends \KrameWork\Reporting\ReportRunner
	{
		public function __construct(IDataCache $cache, $key, $cacheTTL = 300) {
			parent::__construct($cache, $key, $cacheTTL);
		}

		/**
		 * Executes the report, storing the results in cache.
		 * Override this to implement your report.
		 */
		protected function run() {
			$this->runCalled = true;
			return $this->data;
		}

		protected function postProcess(array $data) {
			$this->postProcessCalled = true;
			return parent::postProcess($data);
		}

		public $runCalled;
		public $postProcessCalled;

		/**
		 * @return ReportColumn[]
		 */
		public function columns() {
			return $this->columns;
		}

		/** @var ReportColumn[] */
		public $columns = [];

		/**
		 * @var mixed
		 */
		public $data = [];
	}