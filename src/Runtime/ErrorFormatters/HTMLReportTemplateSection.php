<?php
	namespace KrameWork\Runtime\ErrorFormatters;

	class HTMLReportTemplateSection
	{
		/**
		 * HTMLReportTemplateSection constructor.
		 *
		 * @api __construct
		 * @param string $data Report data.
		 * @param string $tag Section tag.
		 */
		public function __construct(string $data, string $tag) {
			$this->report = $data;
			$this->tag = $tag;
			$this->replacements = [];
			$this->frames = [];
		}

		/**
		 * Set a replacement a single-frame section.
		 *
		 * @api __set
		 * @param $name string Tag name.
		 * @param $value mixed Replacement value.
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		public function __set($name, $value) {
			$this->replacements[$name] = $value;
		}

		/**
		 * Add a replacement set for a multi-frame section.
		 *
		 * @api addFrame
		 * @param array $data
		 */
		public function addFrame(array $data) {
			$this->frames[] = $data;
		}

		/**
		 * Recompile this section with replaced data.
		 *
		 * @api __toString
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		public function __toString():string {
			preg_match('/<!--' . $this->tag . '-->(.*?)<!--\/' . $this->tag . '-->/si', $this->report, $matches, PREG_OFFSET_CAPTURE);

			if (count($matches) !== 2)
				return $this->report;

			// Rebuild report.
			$content = $matches[1][0];
			$sectionStart = $matches[0][1];
			$reportStart = substr($this->report, 0, $sectionStart);
			$reportEnd = substr($this->report, $sectionStart + \strlen($matches[0][0]));

			if (count($this->frames)) { // Multi-frame section.
				$compiled = '';
				foreach ($this->frames as $frame) {
					$patterns = [];
					$replacements = [];

					foreach ($frame as $pattern => $replacement) {
						$patterns[] = '/<!--' . $pattern . '-->/is';
						$replacements[] = $replacement;
					}

					$compiled .= preg_replace($patterns, $replacements, $content);
				}

				$content = $compiled;
			} else { // Single-frame section.
				$patterns = [];
				$replacements = [];

				foreach ($this->replacements as $pattern => $replacement) {
					$patterns[] = '/<!--' . $pattern . '-->/is';
					$replacements[] = $replacement;
				}

				$content = preg_replace($patterns, $replacements, $content);
			}

			return $reportStart . $content . $reportEnd;
		}

		/**
		 * @var array
		 */
		protected $frames;

		/**
		 * @var string
		 */
		protected $report;

		/**
		 * @var string
		 */
		protected $tag;

		/**
		 * @var array
		 */
		protected $replacements;
	}