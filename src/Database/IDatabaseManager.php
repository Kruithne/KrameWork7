<?php
	namespace KrameWork\Database;

	/**
	 * Base class for a database connection driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	interface IDatabaseManager
	{
		/**
		 * Add a managed table to the database
		 * @param string $name
		 * @param IManagedTable $table
		 */
		public function __set(string $name, IManagedTable $table);

		/**
		 * @param string $name A table name
		 * @return IDatabaseTable|IManagedTable A table driver
		 */
		public function __get(string $name);

		/**
		 * Update the database schema according to managed tables
		 * @return array Managed table names as keys with status for each as values
		 */
		public function updateSchema(): array;
	}