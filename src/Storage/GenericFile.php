<?php
	namespace KrameWork\Storage;

	class GenericFile extends BaseFile {
		/**
		 * Get the data contained in this file.
		 * @return string
		 */
		public function getData():string {
			return $this->data ?? "";
		}

		/**
		 * Set the data contained in this file.
		 * @param string $data
		 */
		public function setData(string $data) {
			$this->data = $data;
		}

		/**
		 * Populate the file object using loaded raw data.
		 * Called directly after a successful read() call.
		 * @param string $data Raw data
		 */
		public function parse(string $data)
		{
			$this->data = $data ?? "";
		}

		/**
		 * Compile the populated data into a writable string.
		 * Called during a write() call for file-writing.
		 * @return string Compiled data.
		 */
		public function compile(): string
		{
			return $this->data ?? "";
		}

		/**
		 * @var string
		 */
		private $data;
	}