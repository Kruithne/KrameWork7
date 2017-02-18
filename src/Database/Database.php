<?php
	/*
	 * Copyright (c) 2017 Morten Nilsen (morten@runsafe.no)
	 * https://github.com/Kruithne/KrameWork7
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

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