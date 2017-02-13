<?php
	namespace KrameWork\Reporting;

	use KrameWork\Caching\IDataCache;

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 */
	abstract class ReportRunner
	{
		/**
		 * ReportRunner constructor.
		 * @param IDataCache $cache The cache location for the report data
		 * @param string $key The key to access the cached report data
		 * @param int $cacheTTL The number of seconds the report should remain cached
		 */
		public function __construct(IDataCache $cache, string $key, int $cacheTTL = 300) {
			$this->cacheTTL = $cacheTTL;
			$this->key = $key;
			$this->cache = $cache;
		}

		/**
		 * Executes the report, storing the results in cache.
		 * Override this to implement your report.
		 */
		protected abstract function Run();

		/**
		 * Executes the report if necessary and returns the cached result set.
		 * @return ReportResults The data set returned by the SQL
		 */
		public function Data() {
			if (!$this->cache->exists($this->key))
				$this->cache->store($this->key, new ReportResults($this->Run()), time() + $this->cacheTTL);
			return $this->cache->__get($this->key);
		}

		/**
		 * Flushes cached data, forcing a fresh run of the report
		 */
		public function Clear() {
			$this->cache->__unset($this->key);
		}

		/**
		 * Override this method to do post-processing of the data returned by the SQL
		 * @param array $data
		 * @return array
		 */
		protected function PostProcess(array $data) {
			return $data;
		}

		/**
		 * @var int Cache Time To Live
		 */
		protected $cacheTTL;

		/**
		 * @var string Cache storage key
		 */
		protected $key;

		/**
		 * @var IDataCache Cache engine
		 */
		protected $cache;
	}
