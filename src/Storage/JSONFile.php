<?php
	namespace KrameWork\Storage;

	require_once(__DIR__ . "/File.php");

	class JSONFile extends File
	{
		/**
		 * JSONFile constructor.
		 * @param string $file Path to the file.
		 * @param bool $useContainer Loaded/inserted data will be contained using an ArrayObject.
		 * @param bool $autoLoad If true and file is provided, will attempt to read on construct.
		 * @throws KrameWorkFileException
		 */
		public function __construct(string $file, bool $useContainer = true, bool $autoLoad = true) {
			$this->useContainer = $useContainer;
			if ($useContainer)
				$this->jsonData = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);

			parent::__construct($file, $autoLoad);
		}

		/**
		 * Get a value from the underlying data object.
		 * @param string $key
		 * @return mixed|null
		 * @throws KrameWorkFileException
		 */
		public function __get($key) {
			$this->verifyDataObject();
			return $this->jsonData[$key] ?? null;
		}

		/**
		 * Set a value of the underlying data object.
		 * @param string $key
		 * @param mixed $value
		 * @throws KrameWorkFileException
		 */
		public function __set($key, $value) {
			$this->verifyDataObject();
			$this->jsonData[$key] = $value;
		}

		/**
		 * Unset a value from the underlying data object.
		 * @param $key
		 */
		public function __unset($key) {
			$this->verifyDataObject();
			unset($this->jsonData[$key]);
		}

		/**
		 * Read data from a file.
		 * @throws KrameWorkFileException
		 */
		public function read() {
			parent::read();
			$decoded = json_decode($this->data, $this->assoc, $this->depth, $this->options);
			if ($decoded === null)
				$this->throwJSONError();

			$this->jsonData = $this->useContainer ? new \ArrayObject($decoded, \ArrayObject::ARRAY_AS_PROPS) : $decoded;
		}

		/**
		 * Save the file to disk.
		 * @param string|null $file Path to save the file. Defaults to loaded file location.
		 * @param bool $overwrite If true and file exists, will overwrite.
		 * @throws KrameWorkFileException
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
		 * @throws KrameWorkFileException
		 */
		private function throwJSONError() {
			throw new KrameWorkFileException("JSON error: " . json_last_error_msg());
		}

		/**
		 * Throw an exception if the internal data object is not initiated.
		 * @throws KrameWorkFileException
		 */
		private function verifyDataObject() {
			if ($this->jsonData === null)
				throw new KrameWorkFileException("Attempt to invoke value on a non-initiated JSON file.");
		}

		/**
		 * Get the data contained by this file (empty until read()).
		 * @return mixed
		 */
		public function getData() {
			return $this->jsonData;
		}

		/**
		 * Set the data for this file (requires save() to persist).
		 * @param $data
		 */
		public function setData($data) {
			$this->jsonData = $data;
		}

		/**
		 * Get the raw data for this file.
		 * @return string
		 */
		public function getRawData() {
			return $this->data;
		}

		/**
		 * Set the raw data for this file.
		 * @param $data
		 */
		public function setRawData(string $data) {
			$this->data = $data;
		}

		/**
		 * Set the recursion depth for file reading.
		 * @param int $depth
		 */
		public function setRecursionDepth(int $depth) {
			$this->depth = $depth;
		}

		/**
		 * Set if this file should read objects as associative arrays.
		 * @param bool $assoc
		 */
		public function setAssociative(bool $assoc) {
			$this->assoc = $assoc;
		}

		/**
		 * Set the JSON options bit-mask.
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