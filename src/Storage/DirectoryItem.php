<?php
	namespace KrameWork\Storage;

	use Kramework\Utils\StringUtil;
	require_once(__DIR__ . "/../Utils/StringUtil.php");

	interface IDirectoryItem
	{
		/**
		 * Return the name of this directory item.
		 * @return string
		 */
		public function getName():string;

		/**
		 * Return the path of this directory item.
		 * @return string
		 */
		public function getPath():string;

		/**
		 * Check if this directory item exists.
		 * @return bool
		 */
		public function exists():bool;

		/**
		 * Check if the directory item is valid.
		 * @return bool
		 */
		public function isValid():bool;

		/**
		 * Attempt to delete the directory item.
		 * @return bool
		 */
		public function delete():bool;
	}

	abstract class DirectoryItem implements IDirectoryItem
	{
		/**
		 * DirectoryItem constructor.
		 * @param string $path Path to the directory item.
		 */
		public function __construct($path) {
			$this->path = StringUtil::formatDirectorySlashes($path, true);
			$this->name = basename($path);
		}

		/**
		 * Check if this directory item exists.
		 * @return bool
		 */
		public function exists():bool {
			return file_exists($this->path);
		}

		/**
		 * Return the name of this directory item.
		 * @return string
		 */
		public function getName(): string {
			return $this->name;
		}

		/**
		 * Return the path of this directory item.
		 * @return string
		 */
		public function getPath(): string {
			return $this->path;
		}

		/**
		 * @var string
		 */
		protected $path;

		/**
		 * @var string
		 */
		protected $name;
	}