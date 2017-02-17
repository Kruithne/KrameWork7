<?php
	namespace KrameWork\Runtime\ErrorDispatchers;

	use KrameWork\Runtime\ErrorFormatters\IErrorFormatter;

	/**
	 * Class FileDispatcher
	 * Dispatches errors into flat-files.
	 *
	 * @package KrameWork\Runtime\ErrorDispatchers
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class FileDispatcher implements IErrorDispatcher
	{
		/**
		 * FileDispatcher constructor.
		 *
		 * @api __construct
		 * @param string $directory Directory to store files in.
		 * @param string|array $name File name. Arrays are treated as callbacks.
		 */
		public function __construct(string $directory, $name = 'error') {
			$check = realpath($directory);
			if ($check === false || !is_dir($check))
				$check = __DIR__;

			$this->path = $check;
			$this->name = $name;
		}

		/**
		 * Dispatch an error report.
		 *
		 * @api dispatch
		 * @param IErrorFormatter|string $report Report to dispatch.
		 */
		public function dispatch($report) {
			$file = $this->name;
			if (is_array($file)) // Execute callback.
				$file = call_user_func($file);

			$ext = $report->getExtension();
			$full = $file . $ext;

			// Obtain unique file name.
			if (file_exists($full)) {
				$attemptIndex = 1;
				while (file_exists($full))
					$full = $file . '_' . $attemptIndex++ . $ext;
			}

			file_put_contents($this->path . DIRECTORY_SEPARATOR . $full, $report);
		}

		/**
		 * @var string|array
		 */
		protected $name;

		/**
		 * @var string
		 */
		protected $path;
	}