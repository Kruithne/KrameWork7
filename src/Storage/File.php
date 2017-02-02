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

	require_once(__DIR__ . "/DirectoryItem.php");

	class FileNotFoundException extends \Exception {}
	class FileReadException extends \Exception {}
	class FileWriteException extends \Exception {}

	/**
	 * Class File
	 * @package KrameWork\Storage
	 */
	class File extends DirectoryItem
	{
		/**
		 * File constructor.
		 * @param string $path Path to the file.
		 * @param bool $autoLoad If true and file is provided, will attempt to load on construct.
		 * @param bool $touch If true, file will be created blank on instance construct.
		 * @throws FileNotFoundException|FileReadException
		 */
		public function __construct(string $path, bool $autoLoad = true, $touch = false) {
			parent::__construct($path);

			if ($touch && !$this->exists())
				file_put_contents($this->path, "");

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
		 * Get the size of this file.
		 * @return int
		 */
		public function getSize():int {
			$size = @filesize($this->path);
			return $size !== false ? $size : 0;
		}

		/**
		 * Read data from a file.
		 * @throws FileNotFoundException|FileReadException
		 */
		public function read() {
			if ($this->path === null)
				throw new FileNotFoundException("Cannot resolve file: No path given.");

			if (!file_exists($this->path))
				throw new FileNotFoundException("Cannot resolve file: It does not exist.");

			$raw = file_get_contents($this->path);
			if ($raw === null)
				throw new FileReadException("Unable to read data from file.");

			$this->data = $raw;
		}

		/**
		 * Save the file to disk.
		 * @param string|null $file Path to save the file. Defaults to loaded file location.
		 * @param bool $overwrite If true and file exists, will overwrite.
		 * @throws FileWriteException
		 */
		public function save(string $file = null, bool $overwrite = true) {
			$file = $file ?? $this->path;

			if (!$overwrite && file_exists($file))
				throw new FileWriteException("Cannot write file: Already exists (specify overwrite?)");

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