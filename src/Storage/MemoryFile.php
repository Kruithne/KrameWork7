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

	/**
	 * Class MemoryFile
	 * In-memory file implementation.
	 *
	 * @package KrameWork\Storage
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class MemoryFile extends File
	{
		/**
		 * MemoryFile constructor.
		 * @param string $name
		 * @param string $content
		 * @param string $contentType
		 */
		public function __construct(string $name, string $content, string $contentType = "text/plain") {
			parent::__construct($name, false, false);
			$this->content = $content;
			$this->contentType = $contentType;
			$this->valid = true;
		}

		/**
		 * Check if the file exists and is valid.
		 *
		 * @api
		 * @return bool File exists and is valid.
		 */
		public function isValid(): bool {
			return $this->valid;
		}

		/**
		 * Get the size of this file in bytes.
		 *
		 * @api
		 * @return int Size of the file.
		 */
		public function getSize(): int {
			return strlen($this->content);
		}

		/**
		 * Get the content type of this file.
		 *
		 * @api
		 * @return string MIME type, or "unknown" on failure.
		 */
		public function getFileType(): string {
			return $this->contentType;
		}

		/**
		 * Get the data contained in this file.
		 *
		 * @api
		 * @param bool $forceRead No effect, included for implementation sake.
		 * @return null|string
		 */
		public function getData(bool $forceRead = false) {
			return $this->content;
		}

		/**
		 * Set the data contained in this file.
		 *
		 * @api
		 * @param mixed $data Data to store in the wrapper.
		 */
		public function setData($data) {
			$this->content = $data;
		}

		/**
		 * Attempt to delete the file.
		 *
		 * @api
		 * @return bool File deleted successfully.
		 */
		public function delete(): bool {
			$this->valid = false;
			$this->content = null;
			return true;
		}

		/**
		 * Retrieve the raw data of this file encoded as base64.
		 * @param bool $forceRead No effect, included for implementation sake.
		 * @return string
		 */
		public function getBase64Data(bool $forceRead = false): string {
			return base64_encode($this->content);
		}

		/**
		 * @var string
		 */
		protected $content;

		/**
		 * @var string
		 */
		protected $contentType;

		/**
		 * @var bool
		 */
		protected $valid;
	}