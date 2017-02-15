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

	namespace KrameWork\MVC;

	require_once(__DIR__ . '/Template.php');

	/**
	 * Class PageModule
	 * Basic module implementation with a single contained template.
	 *
	 * @package KrameWork\MVC
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class PageModule
	{
		/**
		 * PageModule constructor.
		 *
		 * @api __construct
		 * @param string $file Template file to use.
		 * @throws InvalidTemplateException
		 */
		public function __construct(string $file) {
			$this->template = new Template($file);
		}

		/**
		 * Get a value from the underlying template.
		 *
		 * @api __get
		 * @param string $name
		 * @return mixed
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __get(string $name) {
			return $this->template->$name;
		}

		/**
		 * Set a value on the underlying template.
		 *
		 * @api __set
		 * @param string $name Name to store the value with.
		 * @param mixed $value Value to store in the template.
		 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
		 */
		function __set(string $name, $value) {
			$this->template->$name = $value;
		}

		/**
		 * Render this module and return the content as a string.
		 *
		 * @api __toString
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString():string {
			return $this->template;
		}

		/**
		 * @var Template
		 */
		protected $template;
	}