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

	require_once(__DIR__ . '/HTMLReportSection.php');

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
			$this->sections = [];
			$this->content = $content;
		}

		/**
		 * Get a section of this template.
		 * Returned section will be retained by this template for compilation.
		 *
		 * @api getSection
		 * @param string $tag Enclosing tags for the section.
		 * @return HTMLReportSection
		 */
		public function getSection(string $tag):HTMLReportSection {
			$section = new HTMLReportSection($tag, $this->content);
			$this->sections[] = $section;
			return $section;
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

			// Section frame replacements.
			foreach ($this->sections as $section) {
				$section->validate($content);
				$contentStart = substr($content, 0, $section->getSectionStart());
				$contentEnd = substr($content, $section->getSectionStart() + $section->getSectionLength());

				$content = $contentStart . $section . $contentEnd;
			}

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
		 * @var HTMLReportSection[]
		 */
		protected $sections;

		/**
		 * @var string
		 */
		protected $content;

		/**
		 * @var array
		 */
		protected $replacements;
	}