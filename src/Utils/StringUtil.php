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

	namespace Kramework\Utils;

	class StringUtil
	{
		/**
		 * Check if a string starts with another string.
		 * @param string $haystack
		 * @param string $needle
		 * @return bool
		 */
		static function startsWith(string $haystack, string $needle):bool {
			return strncmp($haystack, $needle, strlen($needle)) === 0;
		}

		/**
		 * Check if a string ends with another string.
		 * Adapted from http://stackoverflow.com/a/834355/6997644
		 * @param string $haystack
		 * @param string $needle
		 * @return bool
		 */
		static function endsWith(string $haystack, string $needle):bool {
			$length = strlen($needle);
			return (substr($haystack, -$length, $length) === $needle);
		}

		/**
		 * Convert all slashes in a string to match the environment directory separator.
		 * @param string $path Path to clean.
		 * @param bool $trimTrail If true, trailing spaces/slashes will be trimmed.
		 * @return string
		 */
		static function formatDirectorySlashes(string $path, bool $trimTrail = false):string {
			$clean = str_replace(DIRECTORY_SEPARATOR == "/" ? "\\" : "/", DIRECTORY_SEPARATOR, $path);
			return $trimTrail ? rtrim($clean, "\t\n\r\0\x0B\\/") : $clean;
		}

		/**
		 * Get the base class-name from a namespace string.
		 * @param string $namespace
		 * @return string
		 */
		static function namespaceBase(string $namespace):string {
			$parts = explode("\\", $namespace);
			return $parts[count($parts) - 1];
		}
	}