<?php

	use KrameWork\Database\Database;

	require_once(__DIR__.'/../src/Database/Database.php');

	class DatabaseTest extends \PHPUnit\Framework\TestCase
	{
		public function testDsnEncryption() {
			$dsn = 'driver:test';
			$actual = Database::encrypt($dsn, 1);
			$this->assertNotEquals($dsn, $actual);
			$this->assertNotEmpty($actual);
		}

		public function testDsnDecryption() {
			$dsn = 'driver:test';
			$encrypted = Database::encrypt($dsn, 1);
			$decrypted = Database::decrypt($encrypted);
			$this->assertEquals($dsn, $decrypted);
		}
	}