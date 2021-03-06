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
	 * @author Kruithne <kruithne@gmail.com> (KW7 implementation)
	 * @author Andrew Moore (v3, v4, v5 base)
	 * @author Ben Ramsey <ben@benramsey.com> (class constants)
	 */
	class UUID
	{
		/**
		 * When this namespace is specified, the name string is a fully-qualified domain name.
		 * @link http://tools.ietf.org/html/rfc4122#appendix-C
		 */
		const NAMESPACE_DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';

		/**
		 * When this namespace is specified, the name string is a URL.
		 * @link http://tools.ietf.org/html/rfc4122#appendix-C
		 */
		const NAMESPACE_URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';

		/**
		 * When this namespace is specified, the name string is an ISO OID.
		 * @link http://tools.ietf.org/html/rfc4122#appendix-C
		 */
		const NAMESPACE_OID = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';

		/**
		 * When this namespace is specified, the name string is an X.500 DN in DER or a text output format.
		 * @link http://tools.ietf.org/html/rfc4122#appendix-C
		 */
		const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';

		/**
		 * The nil UUID is special form of UUID that is specified to have all 128 bits set to zero.
		 * @link http://tools.ietf.org/html/rfc4122#section-4.1.7
		 */
		const NIL = '00000000-0000-0000-0000-000000000000';

		/**
		 * Generate an RFC 4122 compliant v3 (namespace based) UUID.
		 * Returns false when given an invalid namespace.
		 *
		 * @api generate_v3
		 * @param string $namespace UUID namespace.
		 * @param string $name UUID name.
		 * @return string
		 */
		public static function generate_v3(string $namespace, string $name):string {
			return self::generateNamespaceUUID($namespace, $name, 3);
		}

		/**
		 * Generate an RFC 4122 compliant v4 (pseudo-random) UUID.
		 *
		 * @api generate_v4
		 * @return string
		 */
		public static function generate_v4():string {
			return \sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				\random_int(0, 0xffff), \random_int(0, 0xffff), // 32 bits for "time_low"
				\random_int(0, 0xffff), // 16 bits for "time_mid"
				\random_int(0, 0x0fff) | 0x4000, // 16 bits for "time_hi_and_version", four most significant bits holds version number 4
				\random_int(0, 0x3fff) | 0x8000, // 16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low", two most significant bits holds zero and one for variant DCE1.1
				\random_int(0, 0xffff), \random_int(0, 0xffff), \random_int(0, 0xffff) // 48 bits for "node"
			);
		}

		/**
		 * Generate an RFC 4122 compliant v5 (namespace based) UUID.
		 * Returns false when given an invalid namespace.
		 *
		 * @api generate_v5
		 * @param string $namespace UUID namespace.
		 * @param string $name UUID name.
		 * @return string
		 */
		public static function generate_v5(string $namespace, string $name):string {
			return self::generateNamespaceUUID($namespace, $name, 5);
		}

		/**
		 * Generate an RFC 4122 compliant namespace UUID.
		 * Currently supports v3 and v5.
		 * Returns false when given an invalid namespace or version.
		 *
		 * @internal
		 * @param string $namespace RFC 4122 compliant namespace UUID.
		 * @param string $name Name for generation.
		 * @param int $version UUID version.
		 * @return string
		 */
		private static function generateNamespaceUUID(string $namespace, string $name, int $version):string {
			// Supports v3 or v5 namespace UUID generation.
			if ($version != 3 && $version != 5)
				return self::NIL;

			// Ensure valid namespace UUID.
			if (!self::isValid($namespace))
				return self::NIL;

			$hex = \str_replace(['-', '{', '}'], '', $namespace); // Hex components.
			$bin = ''; // Binary value.

			// Convert namespace to bits.
			for ($i = 0, $l = \strlen($hex); $i < $l; $i += 2)
				$bin .= \chr(\hexdec($hex[$i] . $hex[$i + 1]));

			$hash = $version == 3 ? \md5($bin . $name) : \sha1($bin . $name); // Hash.
			$versionBit = $version == 3 ? 0x3000 : 0x5000;

			return \sprintf('%08s-%04s-%04x-%04x-%12s',
				\substr($hash, 0, 8), // 32 bits for "time_low"
				\substr($hash, 8, 4), // 16 bits for "time_mid"
				(\hexdec(\substr($hash, 12, 4)) & 0x0fff) | $versionBit, // 16 bits for "time_hi_and_version", four most significant bits holds version.
				(\hexdec(\substr($hash, 16, 4)) & 0x3fff) | 0x8000, // 16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low", two most significant bits holds zero and one for variant DCE1.1
				\substr($hash, 20, 12) // 48 bits for "node"
			);
		}

		/**
		 * Check if the given UUID is RFC 4122 compliant.
		 *
		 * @api isValid
		 * @param string $uuid UUID to validate.
		 * @return bool
		 */
		public static function isValid(string $uuid):bool {
			return \preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
		}
	}