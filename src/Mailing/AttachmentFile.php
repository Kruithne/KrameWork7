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

	use KrameWork\Storage\File;
	require_once(__DIR__ . '/../Storage/File.php');

	/**
	 * Class AttachmentFile
	 * Wrapper for attachable files.
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class AttachmentFile extends File
	{
		/**
		 * Set if this attachment should be embedded inline.
		 *
		 * @api setInline
		 * @param bool $inline
		 */
		public function setInline(bool $inline) {
			$this->isInline = $inline;
		}

		/**
		 * Check if this attachment should be inline.
		 *
		 * @api isInline
		 * @return bool
		 */
		public function isInline():bool {
			return $this->isInline;
		}

		/**
		 * Set the content ID of this attachment.
		 *
		 * @api setContentID
		 * @param string $id
		 */
		public function setContentID(string $id) {
			$this->contentID = $id;
		}

		/**
		 * Get the content ID of this attachment.
		 *
		 * @api getContentID
		 * @return string
		 */
		public function getContentID():string {
			return $this->contentID ?? $this->getName();
		}

		/**
		 * @var bool
		 */
		private $isInline;

		/**
		 * @var string
		 */
		private $contentID;
	}