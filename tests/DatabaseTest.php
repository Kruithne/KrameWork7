<?php

	use KrameWork\Database\ConnectionString;
	use KrameWork\Database\Database;
	use KrameWork\Database\EncryptedConnection;

	require_once(__DIR__ . '/../src/Database/Database.php');
	require_once(__DIR__ . '/../src/Database/DatabaseCache.php');
	require_once(__DIR__ . '/../src/Database/EncryptedConnection.php');
	require_once(__DIR__ . '/resources/Database/Fake.php');
	require_once(__DIR__ . '/resources/Cache/DummyCache.php');

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
				$this->assertEquals('could not find driver', $e->getMessage());
			}
		}

		public function testConnectionString() {
			$connection = new ConnectionString('test:dsn', 'user', 'password');
			$this->assertEquals('test:dsn', $connection->__toString());
			$this->assertEquals('user', $connection->getUsername());
			$this->assertEquals('password', $connection->getPassword());
		}

		public function testCachedQuery() {
			$cache = new DummyCache();
			$connection = new ConnectionString('test:dsn', 'user', 'password');
			$database = new Database($connection, Database::DB_DRIVER_FAKE);
			$cached = new \KrameWork\Database\DatabaseCache($cache, $database, 10);
			$data = $cached->getAll('TESTING', [1], 10);
			$key = key($cache->data);
			$this->assertEquals($cache->data[$key], $data);
			$cache->data[$key] = ['testvalue'];
			$data = $cached->getAll('TESTING', [1], 10);
			$this->assertEquals(['testvalue'], $data);
		}
	}