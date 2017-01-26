<?php
	namespace Kramework\Utils;

	class StringUtil {
		/**
		 * Check if a string starts with another string.
		 * @param string $haystack
		 * @param string $needle
		 * @return bool
		 */
		static function startsWith(string $haystack, string $needle):bool
		{
			return strncmp($haystack, $needle, strlen($needle)) === 0;
		}

		/**
		 * Check if a string ends with another string.
		 * Adapted from http://stackoverflow.com/a/834355/6997644
		 * @param string $haystack
		 * @param string $needle
		 * @return bool
		 */
		static function endsWith(string $haystack, string $needle):bool
		{
			$length = strlen($needle);
			return (substr($haystack, -$length, $length) === $needle);
		}

		/**
		 * Convert all slahses in a string to match the environment directory separator.
		 * @param $path
		 * @return string
		 */
		static function formatDirectorySlashes($path):string {
			return str_replace(DIRECTORY_SEPARATOR == "/" ? "\\" : "/", DIRECTORY_SEPARATOR, $path);
		}
	}