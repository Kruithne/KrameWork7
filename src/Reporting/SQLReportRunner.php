<?php
	namespace KrameWork\Reporting;

	use KrameWork\Caching\IDataCache;

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 */
	abstract class SQLReport extends Report
	{
		/**
		 * SQLReport constructor.
		 * @param IDataCache $cache
		 * @param mixed $db Database Access Layer, needs a query() method FIXME Missing API in KW7
		 * @param string $sql An SQL Query
		 * @param array $param Query parameters
		 * @param bool $debug Enable or disable debugging information from the DBMS
		 * @param int $cacheTTL Number of seconds to store the results in cache.
		 */
		public function __construct(IDataCache $cache, $db, string $sql, array $param = [], bool $debug = false, int $cacheTTL = 300) {
			$this->db = $db;
			$this->sql = $sql;
			$this->param = $param;
			$this->debug = $debug;
			parent::__construct($cache, sha1(serialize([$db, $sql, $param])), $cacheTTL);
		}

		/**
		 * Executes the report, storing the results in APC
		 */
		protected function Run() {
			return $this->PostProcess($this->db->query($this->sql, $this->param, DB_RESULT_SET, $this->debug));
		}

		/**
		 * @var mixed Database engine
		 */
		private $db;

		/**
		 * @var string SQL Query
		 */
		private $sql;

		/**
		 * @var array SQL Parameters
		 */
		private $param;

		/**
		 * @var bool Debugging enabled
		 */
		private $debug;
	}
