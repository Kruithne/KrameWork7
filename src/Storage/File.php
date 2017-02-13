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

	require_once(__DIR__ . '/DirectoryItem.php');

	class FileNotFoundException extends \Exception {}
	class FileReadException extends \Exception {}
	class FileWriteException extends \Exception {}

	/**
	 * Class File
	 * Wrapper class for easily managing file-system files.
	 *
	 * @package KrameWork\Storage
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class File extends DirectoryItem
	{
		/**
		 * File constructor.
		 *
		 * @api __construct
		 * @param string $path Path to the file.
		 * @param bool $autoLoad Attempt to load the file contents on instantiation.
		 * @param bool $touch Touch the file, creating it if missing.
		 * @throws FileNotFoundException|FileReadException
		 */
		public function __construct(string $path, bool $autoLoad = true, bool $touch = false) {
			parent::__construct($path);

			if ($touch && !$this->exists())
				file_put_contents($this->path, '');

			if ($autoLoad)
				$this->read();
		}

		/**
		 * Check if the file exists and is valid.
		 *
		 * @api isValid
		 * @return bool
		 */
		public function isValid():bool {
			return $this->exists() && is_file($this->path);
		}

		/**
		 * Attempt to delete the file.
		 *
		 * @api delete
		 * @return bool
		 */
		public function delete():bool {
			return @unlink($this->path);
		}

		/**
		 * Get the size of this file in bytes.
		 *
		 * @api getSize
		 * @return int
		 */
		public function getSize():int {
			$size = @filesize($this->path);
			return $size !== false ? $size : 0;
		}

		/**
		 * Get the extension of this file (without leading period).
		 *
		 * @api getExtension
		 * @return string
		 */
		public function getExtension():string {
			$parts = explode(".", $this->name);
			$size = count($parts);
			return $size > 1 ? $parts[$size - 1] : '';
		}

		/**
		 * Attempts to get the MIME type of a file.
		 * Requires php_fileinfo extension to be enabled.
		 * Returns 'unknown' on failure.
		 *
		 * @api getFileType
		 * @return string
		 */
		public function getFileType():string {
			if (function_exists('finfo_file')) {
				$info = new \finfo(FILEINFO_MIME_TYPE);
				$type = $info->file($this->path);

				if ($type !== null && strlen($type) > 0)
					return $type;
			}
			return 'unknown';
		}

		/**
		 * Attempt to read the data from a file.
		 * Read data is not returned, but available through the wrapper.
		 *
		 * @api read
		 * @throws FileNotFoundException
		 * @throws FileReadException
		 */
		public function read() {
			if ($this->path === null)
				throw new FileNotFoundException('Cannot resolve file: No path given.');

			if (!file_exists($this->path))
				throw new FileNotFoundException('Cannot resolve file: It does not exist.');

			$raw = file_get_contents($this->path);
			if ($raw === null)
				throw new FileReadException('Unable to read data from file.');

			$this->data = $raw;
		}

		/**
		 * Attempt to save the data in the wrapper to a file.
		 * Using an alternative path to save will not change the original
		 * path stored by the wrapper.
		 *
		 * @api save
		 * @param string|null $file Path to save the file. If omitted, will use wrapper path.
		 * @param bool $overwrite Overwrite the file if it exists.
		 * @throws FileWriteException
		 */
		public function save(string $file = null, bool $overwrite = true) {
			$file = $file ?? $this->path;

			if (!$overwrite && file_exists($file))
				throw new FileWriteException('Cannot write file: Already exists (specify overwrite?)');

			file_put_contents($file, $this->data ?? '');
		}

		/**
		 * Get the data contained by the wrapper after a read() or manual set.
		 *
		 * @api getData
		 * @param bool $forceRead Call read() if data is missing.
		 * @return null|string
		 */
		public function getData(bool $forceRead = false) {
			if ($forceRead && $this->data === null)
				$this->read();

			return $this->data;
		}

		/**
		 * Retrieve the raw data of this file encoded as base64.
		 *
		 * @api getBase64Data
		 * @param bool $forceRead Call read() if data is missing.
		 * @return string
		 */
		public function getBase64Data(bool $forceRead = false):string {
			return base64_encode($this->getData($forceRead));
		}

		/**
		 * Set the data for this file wrapper. Overwrites existing.
		 *
		 * @api setData
		 * @param mixed $data Data to store in the wrapper.
		 */
		public function setData($data) {
			$this->data = $data;
		}

		/**
		 * Data loaded from the file.
		 * @var string|null
		 */
		protected $data;
	}