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

	namespace KrameWork\Storage;

	require_once(__DIR__ . "/File.php");

	class InvalidDirectoryException extends \Exception {}
	class FileAlreadyExistsException extends \Exception {}

	/**
	 * Class Directory
	 * Wrapper class to easily manage a file-system directory.
	 *
	 * @package KrameWork\Storage
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class Directory extends DirectoryItem
	{
		const USE_WRAPPERS = 0x1;
		const INCLUDE_FILES = 0x2;
		const INCLUDE_DIRECTORIES = 0x4;
		const INCLUDE_HIDDEN = 0x8;
		const RETURN_FULL_PATHS = 0x10;
		const INCLUDE_ALL = self::INCLUDE_HIDDEN | self::INCLUDE_DIRECTORIES | self::INCLUDE_FILES;
		const DEFAULT_FLAGS = self::USE_WRAPPERS | self::INCLUDE_FILES | self::INCLUDE_DIRECTORIES;

		/**
		 * Directory constructor.
		 *
		 * @api
		 * @param string $path Path to the directory.
		 * @param bool $create Create directory if missing.
		 */
		public function __construct($path, bool $create = false) {
			parent::__construct($path);

			if (!$this->exists() && $create)
				$this->create();
		}

		/**
		 * Check if the directory exists and is valid.
		 *
		 * @api
		 * @return bool Directory exists and is valid.
		 */
		public function isValid():bool {
			return $this->exists() && is_dir($this->path);
		}

		/**
		 * Attempt to create the directory.
		 *
		 * @api
		 * @param bool $recursive Create all directories in the path.
		 * @param int $mode File permissions (No effect on Windows).
		 * @return bool Directory was created successfully.
		 */
		public function create($recursive = true, $mode = 0777):bool {
			return @mkdir($this->path, $mode, $recursive);
		}

		/**
		 * Attempt to delete the directory.
		 *
		 * @api
		 * @param bool $recursive Recursively delete the directory.
		 * @return bool Directory was deleted successfully.
		 */
		public function delete($recursive = false):bool {
			if ($recursive) {
				$success = true;
				foreach ($this->getItems(self::USE_WRAPPERS | self::INCLUDE_ALL) as $item) {
					$result = null;
					if ($item instanceof Directory)
						$result = $item->delete(true);
					else
						$result = $item->delete();

					if (!$result)
						$success = false;
				}

				if (!$success)
					return false;
			}

			return @rmdir($this->path);
		}

		/**
		 * Retrieve a all items contained in the directory.
		 *
		 * @api
		 * @param int $flags Bit-mask flags to control retrieval.
		 * @return string[]|DirectoryItem[] Array of directory items.
		 * @throws InvalidDirectoryException
		 */
		public function getItems(int $flags = self::DEFAULT_FLAGS):array {
			if (!$this->isValid())
				throw new InvalidDirectoryException("Directory could not be resolved.");

			$handle = @opendir($this->path);
			if ($handle === false)
				throw new InvalidDirectoryException("Unable to access directory.");

			$entries = [];
			while (($entry = readdir($handle)) !== false) {
				if ($entry == "." || $entry == "..")
					continue;

				// Skip hidden entries if not desired.
				if (!($flags & self::INCLUDE_HIDDEN) && $entry[0] == ".")
					continue;

				$path = $this->path . DIRECTORY_SEPARATOR . $entry;
				$return = $flags & self::RETURN_FULL_PATHS ? $path : $entry;
				if (is_dir($path)) {
					if ($flags & self::INCLUDE_DIRECTORIES)
						$entries[] = $flags & self::USE_WRAPPERS ? new Directory($path) : $return;
				} else if ($flags & self::INCLUDE_FILES) {
					$entries[] = $flags & self::USE_WRAPPERS ? new File($path) : $return;
				}
			}
			closedir($handle);
			return $entries;
		}

		/**
		 * Retrieve a list of all files in the directory.
		 * Alias of getItems() with INCLUDE_FILES and ~INCLUDE_DIRECTORIES.
		 *
		 * @api
		 * @param int $flags Bit-mask flags to control retrieval.
		 * @return array Files contained in the directory.
		 */
		public function getFiles($flags = self::DEFAULT_FLAGS):array {
			return $this->getItems(($flags & ~self::INCLUDE_DIRECTORIES) | self::INCLUDE_FILES);
		}

		/**
		 * Retrieve a list of all directories in the directory.
		 * Alias of getItems() with INCLUDE_DIRECTORIES and ~INCLUDE_FILES.
		 *
		 * @api
		 * @param int $flags Bit-mask flags to control retrieval.
		 * @return array Directories contained in the directory.
		 */
		public function getDirectories($flags = self::DEFAULT_FLAGS):array {
			return $this->getItems(($flags & ~self::INCLUDE_FILES) | self::INCLUDE_DIRECTORIES);
		}

		/**
		 * Check if this directory contains an item with the given name.
		 *
		 * @api
		 * @param string $name Name to check the directory for.
		 * @return bool Directory contains something with the given name.
		 */
		public function hasItem(string $name):bool {
			$path = $this->path . DIRECTORY_SEPARATOR . $name;
			return file_exists($path);
		}

		/**
		 * Check if this directory contains a file with the given name.
		 * Mimics hasItem() with additional file type check.
		 *
		 * @api
		 * @param string $name Name of the file to check for.
		 * @return bool Directory contains a file with the given name.
		 */
		public function hasFile(string $name):bool {
			$path = $this->path . DIRECTORY_SEPARATOR . $name;
			return file_exists($path) && is_file($path);
		}

		/**
		 * Check if this directory contains a directory with the given name.
		 * Mimics hasItem() with additional directory type check.
		 *
		 * @api
		 * @param string $name Name of the directory to check for.
		 * @return bool Directory contains a directory with the given name.
		 */
		public function hasDirectory(string $name):bool {
			$path = $this->path . DIRECTORY_SEPARATOR . $name;
			return file_exists($path) && is_dir($path);
		}

		/**
		 * Create a new directory inside this directory.
		 * Directory creation is not guaranteed. Confirm with isValid() check
		 * on the returned Directory wrapper object.
		 *
		 * @api
		 * @param string $name Name of the directory to create.
		 * @param int $mode Permissions mode (No effect on Windows).
		 * @return Directory Wrapper for the created directory.
		 * @throws FileAlreadyExistsException
		 */
		public function createDirectory(string $name, $mode = 0777):Directory {
			if ($this->hasItem($name))
				throw new FileAlreadyExistsException();

			$dir = new Directory($this->path . DIRECTORY_SEPARATOR . $name, false);
			$dir->create(false, $mode);
			return $dir;
		}

		/**
		 * Create a new file inside this directory.
		 * File create is not guaranteed, confirm with isValid() check
		 * on the returned File wrapper object.
		 *
		 * @api
		 * @param string $name Name of the file to create.
		 * @return File Wrapper for the created file.
		 * @throws FileAlreadyExistsException
		 */
		public function createFile(string $name):File {
			if ($this->hasItem($name))
				throw new FileAlreadyExistsException();

			return new File($this->path . DIRECTORY_SEPARATOR . $name, false, true);
		}
	}