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

	namespace KrameWork;

	use KrameWork\Utils\StringUtil;
	require_once(__DIR__ . "/Utils/StringUtil.php");

	class InvalidSourcePathException extends \Exception {}

	/**
	 * Class AutoLoader
	 * @package KrameWork
	 * Handles the automatic loading of files based on class initiation.
	 */
	class AutoLoader
	{
		const RECURSIVE_SOURCING = 0x1;
		const INCLUDE_WORKING_DIRECTORY = 0x2;
		const INCLUDE_KRAMEWORK_DIRECTORY = 0x4;
		const DEFAULT_FLAGS = self::INCLUDE_WORKING_DIRECTORY | self::INCLUDE_KRAMEWORK_DIRECTORY | self::RECURSIVE_SOURCING;

		/**
		 * AutoLoader constructor.
		 * @param array $sources List of sources (strings) or namespace/source key-value array.
		 * @param string[] $extensions Allowed extensions.
		 * @param int $flags Flags to control auto-loading.
		 * @throws InvalidSourcePathException
		 */
		public function __construct(array $sources = null, array $extensions = null, int $flags = self::DEFAULT_FLAGS) {
			$this->sources = [];
			$this->extensions = [];

			// Remove any leading periods from extensions.
			foreach ($extensions ?? ["php"] as $ext)
				$this->extensions[] = ltrim($ext, ".");

			// Pre-compute source paths/maps.
			foreach ($sources ?? [] as $sourceName => $sourcePath) {
				// Verify source path.
				$real = realpath(StringUtil::formatDirectorySlashes($sourcePath));
				if ($real === false)
					throw new InvalidSourcePathException("Invalid source path: " . $sourcePath);

				if (is_string($sourceName)) {
					// Convert namespace separators if needed.
					if (DIRECTORY_SEPARATOR == "/")
						$sourceName = str_replace("\\", DIRECTORY_SEPARATOR, $sourceName);

					$this->sources[] = [$sourceName, $real];
				} else {
					$this->sources[] = $real;
				}
			}

			if ($flags & self::INCLUDE_KRAMEWORK_DIRECTORY)
				$this->sources["KrameWork"] = dirname(__FILE__);

			if ($flags & self::INCLUDE_WORKING_DIRECTORY)
				$this->sources[] = getcwd();

			// Register this auto-loader instance with PHP.
			spl_autoload_register([$this, 'loadClass']);

			$this->flags = $flags;
			$this->enabled = true;
		}

		/**
		 * Attempt to load a given class.
		 * @param $className
		 */
		public function loadClass($className) {
			if (!$this->enabled)
				return;

			$className = StringUtil::formatDirectorySlashes($className);
			$queue = $this->sources;

			$i = 0;
			$queueSize = count($queue);

			while ($i < $queueSize) {
				$class = $className;
				$directory = $queue[$i++];

				if (is_array($directory)) {
					list($namespace, $path) = $directory;
					$namespaceLen = strlen($namespace);

					if (strncmp($class, $namespace, $namespaceLen) !== 0)
						continue;

					$class = trim(substr($class, $namespaceLen), DIRECTORY_SEPARATOR);
					$directory = $path;
				}

				foreach ($this->extensions as $ext) {
					$file = $directory . DIRECTORY_SEPARATOR . $class . '.' . $ext;
					if (file_exists($file)) {
						require_once($file);
						return;
					}
				}

				if ($this->flags & self::RECURSIVE_SOURCING) {
					if ($handle = opendir($directory)) {
						while (($entry = readdir($handle)) !== false) {
							if ($entry == "." || $entry == "..")
								continue;

							$path = $directory . DIRECTORY_SEPARATOR . $entry;
							if (is_dir($path)) {
								array_push($queue, $path);
								$queueSize++;
							}
						}

						closedir($handle);
					}
				}
			}
		}

		/**
		 * Disable this auto-loader.
		 */
		public function disable() {
			$this->enabled = false;
		}

		/**
		 * Enable this auto-loader.
		 */
		public function enable() {
			$this->enabled = true;
		}

		/**
		 * @var array
		 */
		private $sources;

		/**
		 * @var string[]
		 */
		private $extensions;

		/**
		 * @var int
		 */
		private $flags;

		/**
		 * @var bool
		 */
		private $enabled;
	}