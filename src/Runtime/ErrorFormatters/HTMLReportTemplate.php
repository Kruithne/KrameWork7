<?php
	namespace KrameWork\Runtime\ErrorFormatters;

	require_once(__DIR__ . '/HTMLReportTemplateSection.php');

	class HTMLReportTemplate
	{
		/**
		 * HTMLReportTemplate constructor.
		 *
		 * @api __construct
		 * @param string $file
		 */
		public function __construct($file = null) {
			$this->file = $file;
			$this->reset();
		}

		/**
		 * Set a basic replacement value for this template.
		 *
		 * @api __set
		 * @param string $tag Tag to replace.
		 * @param mixed $value Value to set.
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		public function __set(string $tag, string $value) {
			$this->data[$tag] = $value;
		}

		/**
		 * Set a replacement array for this template.
		 *
		 * @api setArray
		 * @param string $tag Tag to search for.
		 * @param array $arr Array to populate using the tag section.
		 */
		public function setArray(string $tag, array $arr) {
			$this->arrays[$tag] = $arr;
		}

		/**
		 * Reset the data contained in this template.
		 *
		 * @api reset
		 */
		public function reset() {
			$this->data = [];
			$this->arrays = [];
		}

		/**
		 * Compile the report and return the output.
		 *
		 * @api __toString
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString():string {
			if (!$this->loadTemplate())
				return 'No error template provided.'; // User sucks.

			$report = $this->template;

			// Multi-frame sections.
			foreach ($this->arrays as $tag => $arrays) {
				$section = new HTMLReportTemplateSection($report, $tag);

				foreach ($arrays as $array)
					$section->addFrame($array);

				$report = $section->__toString();
			}

			// Single replacements.


			return $report;
		}

		/**
		 * Load the template file into this instance.
		 *
		 * @internal
		 * @return bool
		 */
		private function loadTemplate():bool {
			// Check if template is already loaded.
			if ($this->template !== null)
				return true;

			// Attempt to load user provided template.
			if ($this->file !== null && $this->loadTemplateFile($this->file))
				return true;

			// Revert to KW7 error template.
			if ($this->loadTemplateFile(__DIR__ . '/../../../templates/error_report.php'))
				return true;

			return false;
		}

		/**
		 * Attempt to load template data from a file.
		 *
		 * @internal
		 * @param string $file File to attempt to load.
		 * @return bool
		 */
		private function loadTemplateFile(string $file):bool {
			if (file_exists($file) && is_file($file)) {
				$data = file_get_contents($file);
				if ($data !== false) {
					$this->template = $data;
					return true;
				}
			}
			return false;
		}

		/**
		 * @var array
		 */
		protected $data;

		/**
		 * @var array
		 */
		protected $arrays;

		/**
		 * @var string
		 */
		protected $file;

		/**
		 * @var string
		 */
		protected $template;
	}