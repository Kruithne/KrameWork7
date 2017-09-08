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

	namespace KrameWork\API;

	class Request
	{
		/**
		 * Request constructor.
		 * @api __construct
		 * @param $method string The request method
		 * @param $input \ArrayObject|null request body decoded from JSON
		 * @param $args array Get/Post data
		 * @param $path string[] Path information from the request
		 */
		public function __construct($method, $input, $args, $path) {
			$this->method = $method;
			$this->input = $input;
			$this->args = $args;
			$this->path = $path;
		}

		/**
		 * @return string
		 */
		public function getMethod(): string {
			return $this->method;
		}

		/**
		 * @return \ArrayObject
		 */
		public function getInputData(): \ArrayObject {
			return $this->input;
		}

		/**
		 * @return array
		 */
		public function getArguments(): array {
			return $this->args;
		}

		/**
		 * @return string[]
		 */
		public function getPath(): array {
			return $this->path;
		}

		/**
		 * @var string
		 */
		protected $method;

		/**
		 * @var \ArrayObject|null
		 */
		protected $input;

		/**
		 * @var array
		 */
		protected $args;

		/**
		 * @var string[]
		 */
		protected $path;
	}