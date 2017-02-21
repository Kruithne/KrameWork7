<?php
	namespace KrameWork\Reporting\HTMLReport;

	class HTMLReportTemplate
	{
		/**
		 * HTMLReportTemplate constructor.
		 *
		 * @api __construct
		 * @param string $content Content of the template.
		 * @link http://php.net/manual/en/language.oop5.decon.php
		 */
		public function __construct(string $content) {
			$this->replacements = [];
			$this->content = $content;
		}

		/**
		 * Set a simple replacement for this template.
		 *
		 * @api __set
		 * @param string $tag Tag to search for.
		 * @param mixed $value Replacement value.
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		public function __set(string $tag, string $value) {
			$this->replacements[$tag] = $value;
		}

		/**
		 * Compile this template and return it as a string.
		 *
		 * @api __toString
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString():string {
			$content = $this->content;

			// Compile basic replacements.
			$patterns = [];
			$replacements = [];

			foreach ($this->replacements as $tag => $replacement) {
				$patterns[] = '/<!--' . $tag . '-->/is';
				$replacements[] = $replacement;
			}

			$content = preg_replace($patterns, $replacements, $content);
			return $content;
		}

		/**
		 * @var string
		 */
		protected $content;

		/**
		 * @var array
		 */
		protected $replacements;
	}