<?php
	namespace KrameWork\Storage;

	class KrameWorkFileException extends \Exception {}

	class File extends DirectoryItem
	{
		/**
		 * File constructor.
		 * @param string $path Path to the file.
		 * @param bool $autoLoad If true and file is provided, will attempt to load on construct.
		 * @param bool $touch If true, file will be created blank on instance construct.
		 * @throws KrameWorkFileException
		 */
		public function __construct(string $path, bool $autoLoad = true, $touch = false) {
			parent::__construct($path);

			if ($touch)
				touch($this->path);

			if ($autoLoad)
				$this->read();
		}

		/**
		 * Check if the directory file is valid.
		 * @return bool
		 */
		public function isValid():bool {
			return $this->exists() && is_file($this->path);
		}

		/**
		 * Attempt to delete the directory item.
		 * @return bool
		 */
		public function delete():bool {
			return @unlink($this->path);
		}

		/**
		 * Read data from a file.
		 * @throws KrameWorkFileException
		 */
		public function read() {
			if ($this->path === null)
				throw new KrameWorkFileException("Cannot read file: No path given.");

			if (!file_exists($this->path))
				throw new KrameWorkFileException("Cannot read file: It does not exist.");

			$raw = file_get_contents($this->path);
			if ($raw === null)
				throw new KrameWorkFileException("Cannot read file: Read error");

			$this->data = $raw;
		}

		/**
		 * Save the file to disk.
		 * @param string|null $file Path to save the file. Defaults to loaded file location.
		 * @param bool $overwrite If true and file exists, will overwrite.
		 * @throws KrameWorkFileException
		 */
		public function save(string $file = null, bool $overwrite = true) {
			$file = $file ?? $this->path;

			if ($file === null)
				throw new KrameWorkFileException("Save path not provided, and none cached from read() calls.");

			if (!$overwrite && file_exists($file))
				throw new KrameWorkFileException("Cannot write file: Already exists (specify overwrite?)");

			file_put_contents($file, $this->data ?? "");
		}

		/**
		 * Get the data contained by this file (empty until read()).
		 * @return mixed
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Set the data for this file (requires save() to persist).
		 * @param $data
		 */
		public function setData($data) {
			$this->data = $data;
		}

		/**
		 * Data loaded from the file.
		 * @var string
		 */
		protected $data;
	}