<?php
	namespace KrameWork\Database;

	/**
	 * Base class for a database connection driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	interface IDatabaseConnection
	{
		/**
		 * Run a raw SQL query
		 * @param string $sql A raw SQL query to execute
		 * @param array $params Parameters to inject in the query
		 * @param int $mode DB::RESULT_* constant defining how the result should be returned
		 * @return mixed Data according to $mode
		 */
		public function query(string $sql, array $params, int $mode = DB::RESULT_SET);

		/**
		 * Execute a raw SQL statement
		 * @param string $sql A raw SQL statement to execute
		 * @param array $params Parameters to inject in the query
		 * @return bool Success
		 */
		public function exec(string $sql, array $params);
	}