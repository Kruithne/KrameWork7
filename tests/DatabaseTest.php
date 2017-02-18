<?php

	use KrameWork\Database\ConnectionString;
	use KrameWork\Database\Database;
	use KrameWork\Database\EncryptedConnection;

	require_once(__DIR__.'/../src/Database/Database.php');
	require_once(__DIR__.'/../src/Database/EncryptedConnection.php');

	class DatabaseTest extends \PHPUnit\Framework\TestCase
	{
		public function testDsnEncryption() {
			$dsn = 'driver:test';
			$actual = EncryptedConnection::encrypt($dsn, 1);
			$this->assertNotEquals($dsn, $actual);
			$this->assertNotEmpty($actual);
		}

		public function testDsnDecryption() {
			$dsn = 'driver:test';
			$encrypted = EncryptedConnection::encrypt($dsn, 1);
			$decrypted = EncryptedConnection::decrypt($encrypted);
			$this->assertEquals($dsn, $decrypted);
		}

		public function testPdo() {
			$connection = new ConnectionString('driver:test');
			try {
				new Database($connection, Database::DB_DRIVER_PDO);
			} catch (Exception $e) {
				$this->assertEquals('invalid data source name', $e->getMessage());
			}
		}
	}