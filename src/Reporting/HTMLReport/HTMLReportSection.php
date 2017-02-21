<?php
	namespace KrameWork\Reporting\HTMLReport;

	class HTMLReportSection
	{
		/**
		 * HTMLReportSection constructor.
		 *
		 * @api __construct
		 * @param string $tag Section tag.
		 * @param string $content Content containing the section.
		 * @link http://php.net/manual/en/language.oop5.decon.php
		 */
		public function __construct(string $tag, string $content) {
			$this->frames = [];
			preg_match('/<!--' . $tag . '-->(.*?)<!--\/' . $tag . '-->/si', $content, $matches, PREG_OFFSET_CAPTURE);

			if (count($matches) == 2) {
				$this->isValid = true;
				$this->sectionStart = $matches[0][1];
				$this->sectionLength = \strlen($matches[0][0]);
				$this->content = $matches[1][0];
			}
		}

		/**
		 * Check if this section is valid (exists in the template).
		 *
		 * @api isValid
		 * @return bool
		 */
		public function isValid():bool {
			return $this->isValid;
		}

		/**
		 * Get the start index of this section within the template.
		 *
		 * @api getSectionStart
		 * @return int
		 */
		public function getSectionStart():int {
			return $this->sectionStart;
		}

		/**
		 * Get the length of this section within the template.
		 *
		 * @api getSectionLength
		 * @return int
		 */
		public function getSectionLength():int {
			return $this->sectionLength;
		}

		/**
		 * Creates a new frame for this section.
		 * Reference to the returned template is retained by the section for compilation.
		 *
		 * @api createFrame
		 * @return HTMLReportTemplate
		 */
		public function createFrame():HTMLReportTemplate {
			$template = new HTMLReportTemplate($this->content);
			$this->frames[] = $template;
			return $template;
		}

		/**
		 * Compile this section and return the result.
		 *
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString():string {
			$result = '';
			foreach ($this->frames as $frame)
				$result .= $frame;

			return $result;
		}

		/**
		 * @var bool
		 */
		protected $isValid;

		/**
		 * @var HTMLReportTemplate[]
		 */
		protected $frames;

		/**
		 * @var int
		 */
		protected $sectionStart;

		/**
		 * @var int
		 */
		protected $sectionLength;

		/**
		 * @var string
		 */
		protected $content;
	}