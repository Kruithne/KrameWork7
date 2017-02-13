<?php
	namespace KrameWork\Reporting;
	use KrameWork\Caching\IDataCache;

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 */
	abstract class SQLReport extends Report
	{
		public function __construct(IDataCache $cache, $db, string $sql, array $param = [], bool $debug = false, int $cacheTTL = 300)
		{
			$this->db = $db;
			$this->sql = $sql;
			$this->param = $param;
			$this->debug = $debug;
			parent::__construct($cache, sha1(serialize([$db, $sql, $param])), $cacheTTL);
		}

		/**
		 * Executes the report, storing the results in APC
		 */
		protected function Run()
		{
			return $this->PostProcess($this->db->query($this->sql, $this->param, DB_RESULT_SET, $this->debug));
		}

		private $db;
		private $sql;
		private $param;
		private $debug;
	}
