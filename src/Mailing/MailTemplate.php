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

	namespace KrameWork\Mailing;

	/**
	 * Class FileNotFoundException
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class FileNotFoundException extends \Exception {}

	/**
	 * Class MailTemplate
	 * Used to load an e-mail body from a templated file.
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class MailTemplate
	{
		/**
		 * MailTemplate constructor.
		 *
		 * @api
		 * @param string $file Template file to use.
		 * @param array $subs Substitutes for the template.
		 * @throws FileNotFoundException
		 */
		public function __construct(string $file, array $subs) {
			if (!\is_file($file))
				throw new FileNotFoundException('Provided template is not a real file.');

			$data = \file_get_contents($file);
			if ($data === false)
				throw new FileNotFoundException('Provided template file cannot be accessed.');

			$this->raw = $data;
			$this->subs = $subs;
		}

		/**
		 * Compile the template to a string.
		 *
		 * @internal
		 */
		private function compile() {
			if ($this->compiled === null) {
				$comp = $this->raw;
				foreach ($this->subs as $key => $value)
					$comp = \str_replace('{' . $key . '}', $value, $comp);

				$this->compiled = $comp;
			}
			return $this->compiled;
		}

		/**
		 * Return the compiled template.
		 * @return string
		 */
		public function __toString() {
			return $this->compile();
		}

		/**
		 * @var string
		 */
		private $raw;

		/**
		 * @var array
		 */
		private $subs;

		/**
		 * @var string
		 */
		private $compiled;
	}