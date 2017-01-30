<?php
	namespace KrameWork\Storage;

	require_once(__DIR__ . "/DirectoryItem.php");
	require_once(__DIR__ . "/File.php");

	class InvalidDirectoryException extends \Exception {}
	class FileAlreadyExistsException extends \Exception {}

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
		 * @param string $path Path to the directory.
		 * @param bool $create If true, directory will be created if missing.
		 */
		public function __construct($path, $create = false) {
			parent::__construct($path);

			if (!$this->exists() && $create)
				$this->create();
		}

		/**
		 * Check if the directory is valid.
		 * @return bool
		 */
		public function isValid(): bool {
			return $this->exists() && is_dir($this->path);
		}

		/**
		 * Attempt to create this directory.
		 * @param bool $recursive Create all directories in the path.
		 * @param int $mode File permissions (Non-Windows OS).
		 * @return bool
		 */
		public function create($recursive = true, $mode = 0777):bool {
			return @mkdir($this->path, $mode, $recursive);
		}

		/**
		 * Attempt to delete the directory item.
		 * @param bool $recursive If true, will recursively delete a directory.
		 * @return bool
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
		 * Retrieve a list of all items in the directory.
		 * @param int $flags
		 * @return string[]|DirectoryItem[]
		 * @throws InvalidDirectoryException
		 */
		public function getItems($flags = self::DEFAULT_FLAGS) {
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
		 * @param int $flags
		 * @return array
		 */
		public function getFiles($flags = self::DEFAULT_FLAGS) {
			return $this->getItems(($flags & ~self::INCLUDE_DIRECTORIES) | self::INCLUDE_FILES);
		}

		/**
		 * Retrieve a list of all directories in the directory.
		 * @param int $flags
		 * @return array
		 */
		public function getDirectories($flags = self::DEFAULT_FLAGS) {
			return $this->getItems(($flags & ~self::INCLUDE_FILES) | self::INCLUDE_DIRECTORIES);
		}

		/**
		 * Check if this directory contains an item with the given name.
		 * @param string $name
		 * @return bool
		 */
		public function hasItem(string $name):bool {
			$path = $this->path . DIRECTORY_SEPARATOR . $name;
			return file_exists($path);
		}

		/**
		 * Check if this directory contains a file with the given name.
		 * @param string $name
		 * @return bool
		 */
		public function hasFile(string $name):bool {
			$path = $this->path . DIRECTORY_SEPARATOR . $name;
			return file_exists($path) && is_file($path);
		}

		/**
		 * Check if this directory contains a directory with the given name.
		 * @param string $name
		 * @return bool
		 */
		public function hasDirectory(string $name):bool {
			$path = $this->path . DIRECTORY_SEPARATOR . $name;
			return file_exists($path) && is_dir($path);
		}

		/**
		 * Create a new directory inside this directory.
		 * @param string $name Name of the directory.
		 * @param int $mode
		 * @return Directory
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
		 * @param string $name
		 * @return File
		 * @throws FileAlreadyExistsException
		 */
		public function createFile(string $name):File {
			if ($this->hasItem($name))
				throw new FileAlreadyExistsException();

			return new File($this->path . DIRECTORY_SEPARATOR . $name, false, true);
		}
	}