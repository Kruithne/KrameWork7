<?php
	namespace KrameWork\Runtime\ErrorReports;

	use KrameWork\Runtime\ErrorTypes\IError;
	require_once(__DIR__ . '/IErrorReport.php');

	class ErrorReport implements IErrorReport
	{
		/**
		 * ErrorReport constructor.
		 *
		 * @api __construct
		 * @param IError $error Object representing the error.
		 * @param string $contentType Content-type of this report.
		 * @param string $extension Extension for storing this file.
		 * @param string $report
		 */
		public function __construct(IError $error, string $contentType, string $extension, string $report) {
			$this->error = $error;
			$this->contentType = $contentType;
			$this->extension = $extension;
			$this->report = $report;
		}

		/**
		 * Get the underlying error this report is for.
		 *
		 * @apu getError
		 * @return IError
		 */
		public function getError(): IError {
			return $this->error;
		}

		/**
		 * Get the content type of this report.
		 *
		 * @api getContentType
		 * @return string
		 */
		public function getContentType(): string {
			return $this->contentType;
		}

		/**
		 * Get the file extension to be used when saving this report
		 * to a file.
		 *
		 * @api getExtension
		 * @return string
		 */
		public function getExtension(): string {
			return $this->extension;
		}

		/**
		 * Get the compiled contents of this report.
		 *
		 * @api __toString
		 * @return string
		 */
		public function __toString(): string {
			return $this->report;
		}

		/**
		 * @var IError
		 */
		protected $error;

		/**
		 * @var string
		 */
		protected $contentType;

		/**
		 * @var string
		 */
		protected $extension;

		/**
		 * @var string
		 */
		protected $report;
	}