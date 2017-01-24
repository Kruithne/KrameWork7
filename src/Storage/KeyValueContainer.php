<?php
	namespace KrameWork\Storage;

	class KeyValueContainer {
		/**
		 * KeyValueContainer constructor.
		 * @param KeyValueContainer|mixed|null $copy
		 */
		public function __construct($copy = []) {
			if ($copy instanceof KeyValueContainer)
				$this->data = $copy->asArray();
			else if (is_string($copy))
				$this->unserialize($copy);
			else
				$this->data = (array)$copy;
		}

		/**
		 * Retrieve a value from this container.
		 * @param mixed $key
		 * @return mixed|null
		 */
		public function __get($key) {
			return $this->data[$key] ?? null;
		}

		/**
		 * Set a value in this container.
		 * @param mixed $key
		 * @param mixed $value
		 */
		public function __set($key, $value) {
			$this->data[$key] = $value;
		}

		/**
		 * Unset a value in this container.
		 * @param string $key
		 */
		public function __unset($key) {
			if (array_key_exists($key, $this->data))
				unset($this->data[$key]);
		}

		/**
		 * Serialize this container to a string.
		 * @return string
		 */
		public function serialize() {
			return serialize($this->data);
		}

		/**
		 * Load the data for this container from a string.
		 * Same as constructing the class with a string constructor.
		 * @param string $data Serialized data.
		 */
		public function unserialize(string $data) {
			$this->data = unserialize($data);
		}

		/**
		 * Specify data which should be serialized to JSON
		 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
		 * @return mixed data which can be serialized by <b>json_encode</b>,
		 * which is a value of any type other than a resource.
		 * @since 5.4.0
		 */
		function jsonSerialize() {
			return (object)$this->values;
		}

		/**
		 * Return the internal array for this container.
		 * @return array
		 */
		public function asArray() {
			return $this->data;
		}

		protected $data;
	}