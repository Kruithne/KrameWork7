<?php
	namespace KrameWork\Database;

	/**
	 * Base class for a database table driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	interface IManagedTable
	{
		/**
		 * Delete the table from the database
		 * @return bool Success
		 */
		public function drop();

		/**
		 * Create the table in the database
		 * @return bool Success
		 */
		public function create();
	}