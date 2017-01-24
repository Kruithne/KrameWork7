<?php
	namespace KrameWork\Storage;

	class JSONFile extends BaseFile {
		/**
		 * JSONFile constructor.
		 * @param bool $container Create a KeyValueContainer for the internal data.
		 * @param string $file Initial file to load.
		 * @param bool $assoc Convert objects to associative arrays.
		 * @param int $depth Recursion depth.
		 * @param int $options Bit-mask of JSON options.
		 */
		public function __construct(bool $container, string $file = null, bool $assoc = false, int $depth = 512, int $options = 0)
		{
			if ($file !== null) {
				$this->read($file, $container, $assoc, $depth, $options);
			} else {
				$this->data = new KeyValueContainer();
			}

			parent::__construct(null);
		}

		/**
		 * Get a value from the underlying data object.
		 * @param string $key
		 * @return mixed|null
		 * @throws KrameWorkFileException
		 */
		public function __get($key) {
			if ($this->data === null)
				throw new KrameWorkFileException("Attempt to access non-initiated JSON file.");

			return $this->data->__get($key);
		}

		/**
		 * Set a value of the underlying data object.
		 * @param string $key
		 * @param mixed $value
		 * @throws KrameWorkFileException
		 */
		public function __set($key, $value) {
			if ($this->data === null)
				throw new KrameWorkFileException("Attempt to set value to a non-initiated JSON file.");

			$this->data->__set($key, $value);
		}

		/**
		 * Get the raw data contained in this JSON wrapper.
		 * @return KeyValueContainer|mixed
		 */
		public function getRawData() {
			return $this->data;
		}

		/**
		 * Set the raw data contained in this JSON wrapper.
		 * @param $data
		 */
		public function setRawData($data) {
			$this->data = $data;
		}

		/**
		 * Read data from a file.
		 * @param string $file Path to the file.
		 * @param bool $containData Place decoded data into a KeyValueContainer
		 * @param bool $assoc Convert objects to associative arrays.
		 * @param int $depth Recursion depth.
		 * @param int $options Bit-mask of JSON options.
		 */
		public function read(string $file, bool $containData = true, bool $assoc = false, int $depth = 512, int $options = 0)
		{
			$this->containData = $containData;
			$this->assoc = $assoc;
			$this->depth = $depth;
			$this->options = $options;
			parent::read($file);
		}

		/**
		 * Populate the file object using loaded raw data.
		 * Called directly after a successful read() call.
		 * @param string $data Raw data.
		 * @throws KrameWorkFileException
		 */
		public function parse(string $data)
		{
			$this->data = json_decode($this->rawData, $this->assoc, $this->depth, $this->options);
			if ($this->data !== null) {
				if ($this->containData)
					$this->data = new KeyValueContainer($this->data);
			} else {
				$this->throwJSONError();
			}
		}

		/**
		 * Compile the populated data into a writable string.
		 * Called during a write() call for file-writing.
		 * @return string Compiled data.
		 * @throws KrameWorkFileException
		 */
		public function compile(): string
		{
			$encoded = json_encode($this->data);
			if ($encoded === null)
				$this->throwJSONError();

			return json_encode($this->data);
		}

		/**
		 * Throw the latest JSON error as an exception.
		 * @throws KrameWorkFileException
		 */
		private function throwJSONError() {
			throw new KrameWorkFileException("JSON error: " . json_last_error_msg());
		}

		/**
		 * @var mixed Internal data object.
		 */
		protected $data;

		/**
		 * @var bool Contain decoded data.
		 */
		protected $containData;

		/**
		 * @var int Recursion depth.
		 */
		protected $depth;

		/**
		 * @var bool Convert objects into associative arrays.
		 */
		protected $assoc;

		/**
		 * @var int Bit-mask for JSON encoding options.
		 */
		protected $options;
	}