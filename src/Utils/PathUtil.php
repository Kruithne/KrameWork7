<?php
	namespace KrameWork\Utils;

	class PathUtil {
		/**
		 * Convert all slashes in a string to the current environment standard.
		 * @param string $path
		 * @return string
		 */
		static function formatSlashes(string $path):string {
			return str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $path);
		}
	}