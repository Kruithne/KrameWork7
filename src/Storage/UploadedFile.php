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

	require_once(__DIR__ . "/File.php");

	/**
	 * Class UploadedFile
	 * Wrapper class for easily managing uploaded files.
	 *
	 * @package KrameWork\Storage
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class UploadedFile extends File
	{
		/**
		 * UploadedFile constructor.
		 *
		 * @api __construct
		 * @param string $path Temporary location.
		 * @param string $name Uploaded name.
		 * @param int $errorCode Upload error code.
		 */
		public function __construct(string $path, string $name, int $errorCode) {
			parent::__construct($path, false, false);
			$this->name = $name;
			$this->errorCode = $errorCode;
		}

		/**
		 * Check if the uploaded file is valid.
		 *
		 * @api isValid
		 * @return bool
		 */
		public function isValid(): bool {
			return $this->errorCode === UPLOAD_ERR_OK && parent::isValid();
		}

		/**
		 * Get the error code for this upload.
		 *
		 * @api getErrorCode
		 * @return int
		 */
		public function getErrorCode():int {
			return $this->errorCode;
		}

		/**
		 * Copy the state of one file, to another.
		 *
		 * @api marshalFrom
		 * @param File $file
		 */
		public function marshalFrom(File $file) {
			parent::marshalFrom($file);

			if ($file instanceof UploadedFile)
				$this->errorCode = $file->errorCode;
		}


		/**
		 * @var int Error code for the upload.
		 */
		protected $errorCode;
	}