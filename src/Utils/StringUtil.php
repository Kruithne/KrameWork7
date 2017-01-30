<?php
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