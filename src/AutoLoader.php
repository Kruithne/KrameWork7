<?php
	namespace KrameWork;

	/**
	 * Class AutoLoader
	 * @package KrameWork
	 * Handles the automatic loading of files based on class initiation.
	 */
	class AutoLoader {
		/**
		 * AutoLoader constructor.
		 */
		public function __construct()
		{
		}

		/**
		 * @var string[] Filters for which files are loaded.
		 */
		private $filters;
	}