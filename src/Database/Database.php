<?php

	namespace KrameWork\Database;

	require_once(__DIR__.'/IDatabaseConnection.php');
	require_once(__DIR__.'/IDatabaseManager.php');
	require_once(__DIR__.'/IDatabaseTable.php');
	require_once(__DIR__.'/IManagedTable.php');
	require_once(__DIR__.'/IQueryPredicate.php');

	final class DB
	{
		const RESULT_SET = 0;
		const RESULT_ROW = 1;
		const RESULT_COL = 2;
		const RESULT_ONE = 3;
	}

	/**
	 * Base class for a database connection driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	abstract class DatabaseConnection implements IDatabaseManager, IDatabaseConnection
	{
	}

	/**
	 * Base class for a database table driver
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	abstract class DatabaseTable implements IQueryPredicate, IDatabaseTable, IManagedTable
	{
	}