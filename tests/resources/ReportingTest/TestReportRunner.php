<?php
	require_once(__DIR__ . '/../../../src/Reporting/ReportRunner.php');

	class TestReportRunner extends \KrameWork\Reporting\ReportRunner
	{
		public function __construct(\KrameWork\Caching\IDataCache $cache, $key, $cacheTTL = 300, $data) {
			parent::__construct($cache, $key, $cacheTTL);
			$this->data = $data;
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
			return $data;
		}

		public $runCalled;
		public $postProcessCalled;

		/**
		 * @var mixed
		 */
		public $data;
	}