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

	class JSONException extends \Exception {}

	/**
	 * Class JSONFile
	 * Wrapper class for easily managing JSON files.
	 *
	 * @package KrameWork\Storage
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class JSONFile extends File
	{
		/**
		 * JSONFile constructor.
		 *
		 * @api
		 * @param string $file Path to the file.
		 * @param bool $useContainer Loaded/inserted data will be contained using an ArrayObject.
		 * @param bool $autoLoad Attempt to read data from the file on instantiation.
		 * @throws JSONException
		 */
		public function __construct(string $file, bool $useContainer = true, bool $autoLoad = true) {
			$this->useContainer = $useContainer;
			if ($useContainer)
				$this->jsonData = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);

			parent::__construct($file, $autoLoad);
		}

		/**
		 * Obtain a value from the JSON container.
		 *
		 * @param string $key Key to lookup.
		 * @return mixed|null Value, or null if not found.
		 * @throws JSONException
		 */
		public function __get(string $key) {
			$this->verifyDataObject();
			return $this->jsonData[$key] ?? null;
		}

		/**
		 * Set the value of a specific key in the JSON container.
		 *
		 * @param string $key Key to store the value for.
		 * @param mixed $value Value to store at the given key.
		 * @throws JSONException
		 */
		public function __set(string $key, $value) {
			$this->verifyDataObject();
			$this->jsonData[$key] = $value;
		}

		/**
		 * Remove a value from the JSON container with the given key.
		 *
		 * @param string $key Key to remove from the container.
		 */
		public function __unset(string $key) {
			$this->verifyDataObject();
			unset($this->jsonData[$key]);
		}

		/**
		 * Attempt to read and decode JSON data from the file.
		 * Read data is not returned, but made available through the wrapper.
		 *
		 * @api
		 * @throws JSONException
		 */
		public function read() {
			parent::read();

			$decoded = json_decode($this->data, $this->assoc, $this->depth, $this->options);
			if ($decoded === null)
				$this->throwJSONError();

			$this->jsonData = $this->useContainer ? new \ArrayObject($decoded, \ArrayObject::ARRAY_AS_PROPS) : $decoded;
		}

		/**
		 * Save the contents of the JSON container to a file.
		 * Using an alternative path to save will not change the original
		 * path stored by the wrapper.
		 *
		 * @api
		 * @param string|null $file Path to save the file. If omitted, uses original file path.
		 * @param bool $overwrite Overwrite file if it exists.
		 * @throws JSONException
		 */
		public function save(string $file = null, bool $overwrite = true) {
			$encoded = json_encode($this->jsonData);
			if ($encoded === null)
				$this->throwJSONError();

			$this->data = $encoded;
			parent::save($file, $overwrite);
		}

		/**
		 * Throw the latest JSON error as an exception.
		 *
		 * @internal
		 * @throws JSONException
		 */
		private function throwJSONError() {
			throw new JSONException("JSON error: " . json_last_error_msg());
		}

		/**
		 * Throw an exception if the internal data object is not initiated.
		 *
		 * @internal
		 * @throws JSONException
		 */
		private function verifyDataObject() {
			if ($this->jsonData === null)
				throw new JSONException("Attempt to invoke value on a non-initiated JSON file.");
		}

		/**
		 * Get the JSON container inside this wrapper.
		 * Returns a string if not using containers (see constructor).
		 *
		 * @api
		 * @return \ArrayObject|string|null
		 */
		public function getData() {
			return $this->jsonData;
		}

		/**
		 * Set the data contained by this wrapper.
		 * Not recommended unless not using containers (see constructor).
		 *
		 * @api
		 * @param $data
		 */
		public function setData($data) {
			$this->jsonData = $data;
		}

		/**
		 * Get the raw data string for this wrapper.
		 *
		 * @api
		 * @return string|null Raw data, or null if not read/set.
		 */
		public function getRawData() {
			return $this->data;
		}

		/**
		 * Set the raw data string for this file.
		 *
		 * @api
		 * @param string $data
		 */
		public function setRawData(string $data) {
			$this->data = $data;
		}

		/**
		 * Set the recursion depth for file reading.
		 * Defaults to 512 if not set.
		 *
		 * @api
		 * @param int $depth
		 */
		public function setRecursionDepth(int $depth) {
			$this->depth = $depth;
		}

		/**
		 * Set if this file should read objects as associative arrays.
		 * Defaults to false if not set.
		 *
		 * @api
		 * @param bool $assoc
		 */
		public function setAssociative(bool $assoc) {
			$this->assoc = $assoc;
		}

		/**
		 * Set the JSON options bit-mask.
		 *
		 * @api
		 * @param int $mask
		 */
		public function setOptions(int $mask) {
			$this->options = $mask;
		}

		/**
		 * @var \ArrayObject|string
		 */
		protected $jsonData;

		/**
		 * @var bool Store data inside an ArrayObject.
		 */
		protected $useContainer;

		/**
		 * @var int Recursion depth.
		 */
		protected $depth = 512;

		/**
		 * @var bool Convert objects into associative arrays.
		 */
		protected $assoc = false;

		/**
		 * @var int Bit-mask for JSON encoding options.
		 */
		protected $options = 0;
	}