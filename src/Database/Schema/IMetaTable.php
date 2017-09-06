<?php

	namespace KrameWork\Database\Schema;

	interface IMetaTable
	{
		/**
		 * @param string $table
		 * @return int
		 */
		public function getVersion(string $table) : int;

		/**
		 * @param string $table
		 * @param int $version
		 */
		public function setVersion(string $table, int $version);
	}