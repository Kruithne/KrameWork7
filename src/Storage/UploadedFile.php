<?php
	namespace KrameWork\Storage;

	require_once(__DIR__ . "/File.php");

	class UploadedFile extends File
	{
		/**
		 * UploadedFile constructor.
		 * @param string $path Temporary location.
		 * @param string $name Uploaded name.
		 * @param bool $autoLoad Load the data for the file on construction.
		 */
		public function __construct(string $path, string $name, $autoLoad = true) {
			parent::__construct($path, $autoLoad, false);
			$this->name = $name;
		}
	}