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

	namespace KrameWork\Storage;

	use Kramework\Utils\StringUtil;
	require_once(__DIR__ . '/../Utils/StringUtil.php');

	/**
	 * Interface IDirectoryItem
	 *
	 * @package KrameWork\Storage
	 * @author Kruithne (kruithne@gmail.com)
	 */
	interface IDirectoryItem
	{
		/**
		 * Return the name of this directory item.
		 *
		 * @api
		 * @return string Name of the directory item.
		 */
		public function getName():string;

		/**
		 * Return the path of this directory item.
		 *
		 * @api
		 * @return string Full path of the directory item.
		 */
		public function getPath():string;

		/**
		 * Check if this directory item exists.
		 *
		 * @api
		 * @return bool Directory item exists.
		 */
		public function exists():bool;

		/**
		 * Check if the directory item is valid.
		 *
		 * @api
		 * @return bool Directory item exists and is valid.
		 */
		public function isValid():bool;

		/**
		 * Attempt to delete the directory item.
		 *
		 * @api
		 * @return bool Deletion success.
		 */
		public function delete():bool;
	}

	/**
	 * Class DirectoryItem
	 * Base class for file-system wrapper classes.
	 *
	 * @package KrameWork\Storage
	 * @author Kruithne (kruithne@gmail.com)
	 */
	abstract class DirectoryItem implements IDirectoryItem
	{
		/**
		 * DirectoryItem constructor.
		 *
		 * @api
		 * @param string $path Path to the directory item.
		 */
		public function __construct($path) {
			$this->path = StringUtil::formatDirectorySlashes($path, true);
			$this->name = basename($path);
		}

		/**
		 * Check if this directory item exists.
		 *
		 * @api
		 * @return bool Directory item exists.
		 */
		public function exists():bool {
			return file_exists($this->path);
		}

		/**
		 * Return the name of this directory item.
		 *
		 * @api
		 * @return string Name of this directory item.
		 */
		public function getName():string {
			return $this->name;
		}

		/**
		 * Return the path of this directory item.
		 *
		 * @api
		 * @return string Full path of this directory item.
		 */
		public function getPath():string {
			return $this->path;
		}

		/**
		 * @var string
		 */
		protected $path;

		/**
		 * @var string
		 */
		protected $name;
	}