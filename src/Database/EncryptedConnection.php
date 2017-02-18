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

	/**
	 * Class for a encrypted database connection string
	 * @author docpify <morten@runsafe.no>
	 * @package KrameWork\Database
	 */
	class EncryptedConnection extends ConnectionString
	{
		/**
		 * @api __toString
		 * @return string The decrypted connection string
		 */
		public function __toString() {
			$connection = base64_decode($this->connection);
			switch (substr($connection, 0, 3)) {
				case 'H1$':
					$connection = $this->decrypt($connection);
					break;
			}
			return $connection;
		}

		/**
		 * Encrypt a connection string
		 * @api encrypt
		 * @param $dsn string A connection string
		 * @param $type int Encryption format
		 * @return string An encrypted connection string
		 */
		public static function encrypt(string $dsn, int $type) {
			$salt = base64_encode(random_bytes(16));
			if ($type == 1)
				return base64_encode('H1$' . $salt . '$' . \openssl_encrypt($dsn, 'AES-256-ECB', self::getKey(1, $salt)));
			return $dsn;
		}

		/**
		 * Decrypt a connection string
		 * @param string $dsn An encrypted connection string
		 * @return string The decrypted connection string
		 */
		public static function decrypt(string $dsn): string {
			$dsn = base64_decode($dsn);
			if (preg_match('/^H(\\d)\\$([^$]*)\\$(.*)$/', $dsn, $matches)) {
				$type = $matches[1];
				$salt = $matches[2];
				$payload = $matches[3];
				$key = self::getKey($type, $salt);
				return \openssl_decrypt($payload, 'AES-256-ECB', $key);
			}
			return $dsn;
		}

		/**
		 * Generate the cypher key
		 * @param int $type Encryption type
		 * @param string $salt A generated salt
		 * @return string An encryption key in base 64 format
		 * @throws \Exception If type is not 1
		 */
		private static function getKey(int $type, string $salt): string {
			if ($type != 1)
				throw new \Exception("Unrecognized connection string encoding");

			$in = [4, 8, 6, 32, 99, 21, 2];
			$out = [];
			foreach ($in as $k => $v)
				$out[] = $k * sin($v);

			return base64_encode(sha1($salt . json_encode($out), true));
		}
	}