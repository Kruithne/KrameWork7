<?php
	namespace KrameWork\Reporting;

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 */
	abstract class ReportRunner
	{
		public function __construct(string $key, int $cacheTTL = 300)
		{
			$this->cacheTTL = $cacheTTL;
			$this->key = $key;
		}

		/**
		 * Executes the report, storing the results in APC
		 */
		protected abstract function Run();

		/**
		 * Executes the report if necessary and returns the cached result set.
		 * @return ReportResults The data set returned by the SQL
		 */
		public function Data()
		{
			if (!apcu_exists($this->key))
				apcu_store($this->key, new ReportResults($this->Run()), $this->cacheTTL);
			return apcu_fetch($this->key);
		}

		/**
		 * Flushes cached data, forcing a fresh run of the report
		 */
		public function Clear()
		{
			apcu_delete($this->key);
		}

		/**
		 * Override this method to do post-processing of the data returned by the SQL
		 * @param array $data
		 * @return array
		 */
		protected function PostProcess(array $data)
		{
			return $data;
		}

		protected $cacheTTL;
		protected $key;
	}
