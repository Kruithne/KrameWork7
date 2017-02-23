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

	namespace KrameWork\Reporting\HTMLReport;

	/**
	 * Class HTMLReportSection
	 * Represents a specific section in a HTML template.
	 *
	 * @package KrameWork\Reporting\HTMLReport
	 * @author Kruithne <kruithne@gmail.com>
	 */
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
			$this->pattern = '/<!--' . $tag . '-->(.*?)<!--\/' . $tag . '-->/si';

			$this->content = '';
			$this->isValid = false;
			$this->validate($content);
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
		 * Validate the content/position of this section in the given chunk.
		 *
		 * @api validate
		 * @param string $content Content that contains the section.
		 * @return bool
		 */
		public function validate(string $content):bool {
			preg_match($this->pattern, $content, $matches, PREG_OFFSET_CAPTURE);

			if (count($matches) == 2) {
				$this->isValid = true;
				$this->sectionStart = $matches[0][1];
				$this->sectionLength = \strlen($matches[0][0]);
				$this->content = $matches[1][0];
				return true;
			}
			return false;
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
		 * @var string
		 */
		protected $pattern;

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