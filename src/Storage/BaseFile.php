<?php
	namespace KrameWork\Storage;

	class KrameWorkFileException extends \Exception {}

	interface IFileFormat {
		/**
		 * Populate the file object using loaded raw data.
		 * Called directly after a successful read() call.
		 * @param string $data Data to parse.
		 */
		public function parse(string $data);

		/**
		 * Compile the populated data into a writable string.
		 * Called during a write() call for file-writing.
		 * @return string Compiled data.
		 */
		public function compile():string;

		/**
		 * Read data from a file.
		 * @param string $file Path to the file.
		 */
		public function read(string $file);

		/**
		 * Save the file to disk.
		 * @param string $file Path to save the file.
		 * @param bool $overwrite If true and file exists, will overwrite.
		 */
		public function save(string $file, bool $overwrite = false);

		/**
		 * Check if the target file exists.
		 * @return bool
		 */
		public function exists():bool;
	}

	abstract class BaseFile implements IFileFormat {
		/**
		 * BaseFile constructor.
		 * @param string|null $initialPath If provided, will attempt to read the file.
		 * @param bool $autoLoad If true and file is provided, will attempt to load on construct.
		 * @throws KrameWorkFileException
		 */
		public function __construct(string $initialPath = null, bool $autoLoad = true) {
			$this->cachePath = $initialPath;
			if ($this->cachePath !== null && $autoLoad)
				$this->read($this->cachePath);
		}

		/**
		 * Read data from a file.
		 * @param string|null $file Path to the file.
		 * @throws KrameWorkFileException
		 */
		public function read(string $file = null) {
			$file = $file ?? $this->cachePath;

			if ($file === null)
				throw new KrameWorkFileException("Cannot read file: No path given.");

			if (!file_exists($file))
				throw new KrameWorkFileException("Cannot read file: It does not exist.");

			$raw = file_get_contents($file);
			if ($raw === null)
				throw new KrameWorkFileException("Cannot read file: Read error");

			$this->cachePath = $file;
			$this->parse($raw);
		}

		/**
		 * Save the file to disk.
		 * @param string|null $file Path to save the file. Defaults to last read() path.
		 * @param bool $overwrite If true and file exists, will overwrite.
		 * @throws KrameWorkFileException
		 */
		public function save(string $file = null, bool $overwrite = true) {
			$file = $file ?? $this->cachePath;

			if ($file === null)
				throw new KrameWorkFileException("Save path not provided, and none cached from read() calls.");

			if (!$overwrite && file_exists($file))
				throw new KrameWorkFileException("Cannot write file: Already exists (specify overwrite?)");

			file_put_contents($file, $this->compile() ?? "");
		}

		/**
		 * Check if the target file exists.
		 * @return bool
		 */
		public function exists(): bool {
			return $this->cachePath ? file_exists($this->cachePath) : null;
		}

		/**
		 * @var string
		 */
		protected $cachePath;
	}