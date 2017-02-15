<?php

	namespace KrameWork\Database;

	require_once(__DIR__ . '/IDatabaseConnection.php');
	require_once(__DIR__ . '/IDatabaseManager.php');
	require_once(__DIR__ . '/IDatabaseTable.php');
	require_once(__DIR__ . '/IManagedTable.php');
	require_once(__DIR__ . '/IQueryPredicate.php');

	final class DB
	{
		const RESULT_SET = 0;
		const RESULT_ROW = 1;
		const RESULT_COL = 2;
		const RESULT_ONE = 3;
	}

	class Database
	{
		public function __construct(string $connection) {
			$connection = base64_decode($connection);
			switch (substr($connection, 0, 3)) {
				case 'H1$':
					$connection = $this->decrypt($connection);
					break;
			}
			$this->dsn = $connection;
		}

		public static function encrypt($dsn, $type) {
			$salt = base64_encode(random_bytes(16));
			if($type == 1)
				return base64_encode('H1$'.$salt.'$'.\openssl_encrypt($dsn, 'AES-256-ECB', self::getKey(1, $salt)));
			return $dsn;
		}

		public static function decrypt($dsn): string {
			$dsn = base64_decode($dsn);
			if(preg_match('/^H(\\d)\\$([^$]*)\\$(.*)$/', $dsn, $matches)) {
				$type = $matches[1];
				$salt = $matches[2];
				$payload = $matches[3];
				$key = self::getKey($type, $salt);
				return \openssl_decrypt($payload, 'AES-256-ECB', $key);
			}
			return $dsn;
		}

		private static function getKey(int $type, string $salt): string {
			if($type != 1)
				throw new \Exception("Unrecognized connection string encoding");

			$in = [4,8,6,32,99,21,2];
			$out = [];
			foreach($in as $k => $v)
				$out[] = $k * sin($v);

			return base64_encode(sha1($salt.json_encode($out), true));
		}

		private $dsn;
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