<?php
	namespace KrameWork\MVC;

	class InvalidTemplateException extends \Exception {}

	class Template
	{
		/**
		 * Template constructor.
		 *
		 * @api __construct
		 * @param string $file Path to a template file.
		 * @throws InvalidTemplateException
		 */
		public function __construct(string $file) {
			if (!file_exists($file))
				throw new InvalidTemplateException('Template source does not exist.');

			if (!is_file($file))
				throw new InvalidTemplateException('Template source is not a file.');

			$this->file = $file;
			$this->data = [];
		}

		/**
		 * Obtain a value stored by this template.
		 * Returns null if the key does not exist in the template.
		 *
		 * @api __get
		 * @param string $key Key to get the value for.
		 * @return mixed|null
		 */
		public function __get(string $key) {
			return $this->data[$key] ?? null;
		}

		/**
		 * Set a value to be stored by this template.
		 *
		 * @api __set
		 * @param string $key Key to store the value with.
		 * @param mixed $value Value to store.
		 */
		public function __set(string $key, $value) {
			$this->data[$key] = $value;
		}

		/**
		 * Render this template and return it as a string.
		 */
		public function __toString():string {
			ob_start();
			extract($this->data);

			require($this->file);

			return ob_get_clean();
		}

		/**
		 * @var array
		 */
		protected $data;

		/**
		 * @var string
		 */
		protected $file;
	}