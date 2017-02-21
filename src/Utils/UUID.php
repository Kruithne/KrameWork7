<?php
	/*
	 * Copyright (c) 2017 Kruithne (kruithne@gmail.com)
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

	namespace KrameWork\Utils;

	/**
	 * Class UUID
	 * UUID generation functions.
	 *
	 * @package KrameWork\Utils
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class UUID
	{
		/**
		 * Generate an RFC 4211 compliant v3 UUID.
		 * Returns false when given an invalid namespace.
		 *
		 * @api generate_v3
		 * @param $namespace
		 * @param $name
		 * @return bool|string
		 */
		public static function generate_v3(string $namespace, string $name) {
			return self::generateNamespaceUUID($namespace, $name, 3);
		}

		/**
		 * Generate an RFC 4211 compliant v5 UUID.
		 * Returns false when given an invalid namespace.
		 *
		 * @api generate_v5
		 * @param string $namespace
		 * @param string $name
		 * @return bool|string
		 */
		public static function generate_v5(string $namespace, string $name) {
			return self::generateNamespaceUUID($namespace, $name, 5);
		}

		/**
		 * Generate an RFC 4211 compliant namespace UUID.
		 * Currently supports v3 and v5.
		 * Returns false when given an invalid namespace or version.
		 *
		 * @internal
		 * @param string $namespace RFC 4211 compliant namespace UUID.
		 * @param string $name Name for generation.
		 * @param int $version UUID version.
		 * @return string|bool
		 */
		private static function generateNamespaceUUID(string $namespace, string $name, int $version) {
			// Supports v3 or v5 namespace UUID generation.
			if ($version != 3 && $version != 5)
				return false;

			// Ensure valid namespace UUID.
			if (!self::isValid($namespace))
				return false;

			$hex = str_replace(['-', '{', '}'], '', $namespace); // Hex components.
			$bin = ''; // Binary value.

			// Convert namespace to bits.
			for ($i = 0; $i < \strlen($hex); $i += 2)
				$bin .= chr(hexdec($hex[$i] . $hex[$i + 1]));

			$hash = $version == 3 ? md5($bin . $name) : sha1($bin . $name); // Hash.
			$versionBit = $version == 3 ? 0x3000 : 0x5000;

			return sprintf('%08s-%04s-%04x-%04x-%12s',
				substr($hash, 0, 8), // 32 bits for "time_low"
				substr($hash, 8, 4), // 16 bits for "time_mid"
				(hexdec(substr($hash, 12, 4)) & 0x0fff) | $versionBit, // 16 bits for "time_hi_and_version", four most significant bits holds version.
				(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000, // 16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low", two most significant bits holds zero and one for variant DCE1.1
				substr($hash, 20, 12) // 48 bits for "node"
			);
		}

		/**
		 * Check if the given UUID is RFC 4211 compliant.
		 *
		 * @api isValid
		 * @param string $uuid
		 * @return bool
		 */
		public static function isValid(string $uuid):bool {
			return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
		}
	}