<?php
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
		 * @api
		 * @param string $path Temporary location.
		 * @param string $name Uploaded name.
		 * @param int $errorCode
		 */
		public function __construct(string $path, string $name, int $errorCode) {
			parent::__construct($path, false, false);
			$this->name = $name;
			$this->errorCode = $errorCode;
		}

		/**
		 * Check if the uploaded file is valid.
		 *
		 * @api
		 * @return bool Uploaded file is valid.
		 */
		public function isValid(): bool {
			return $this->errorCode === UPLOAD_ERR_OK && parent::isValid();
		}

		/**
		 * Get the error code for this upload.
		 *
		 * @api
		 * @return int
		 */
		public function getErrorCode():int {
			return $this->errorCode;
		}

		/**
		 * @var int Error code for the upload.
		 */
		protected $errorCode;
	}