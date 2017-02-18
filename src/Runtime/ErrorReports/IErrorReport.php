<?php
	namespace KrameWork\Runtime\ErrorReports;

	use KrameWork\Runtime\ErrorTypes\IError;

	interface IErrorReport
	{
		/**
		 * Get the underlying error this report is for.
		 *
		 * @apu getError
		 * @return IError
		 */
		public function getError():IError;

		/**
		 * Get the content type of this report.
		 *
		 * @api getContentType
		 * @return string
		 */
		public function getContentType():string;

		/**
		 * Get the file extension to be used when saving this report
		 * to a file.
		 *
		 * @api getExtension
		 * @return string
		 */
		public function getExtension():string;

		/**
		 * Get the compiled contents of this report.
		 *
		 * @api __toString
		 * @return string
		 */
		public function __toString():string;
	}