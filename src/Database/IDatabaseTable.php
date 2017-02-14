<?php
	namespace KrameWork\Database;

	/**
	 * Base class for a database table driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	interface IDatabaseTable
	{
		/**
		 * Delete all data from the table
		 * @return bool Success
		 */
		public function truncate();

		/**
		 * @param array $filterSpec Filter specification
		 * @return IQueryPredicate
		 */
		public function where(array $filterSpec);

		/**
		 * @param array $data Array of key/value pairs to add to the table as a new row
		 * @return bool Success
		 */
		public function insert(array $data);

		/**
		 * @return int The id of the last row inserted into the table
		 */
		public function lastAutoId(): int;
	}