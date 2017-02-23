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

	namespace KrameWork\Runtime\ErrorDispatchers;

	use KrameWork\Runtime\ErrorReports\IErrorReport;

	require_once(__DIR__ . '/IErrorDispatcher.php');

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
		 * @param IErrorReport|string $report Report to dispatch.
		 * @return bool
		 */
		public function dispatch($report):bool {
			$file = $this->name;
			if (is_array($file)) // Execute callback.
				$file = call_user_func(count($file) == 1 ? $file[0] : $file, $report);
			elseif (is_callable($file))
				$file = $file($report);

			$ext = ($report instanceof IErrorReport) ? $report->getExtension() : '.txt';
			$full = $file . $ext;

			// Obtain unique file name.
			if (file_exists($full)) {
				$attemptIndex = 1;
				while (file_exists($full))
					$full = $file . '_' . $attemptIndex++ . $ext;
			}

			file_put_contents($this->path . DIRECTORY_SEPARATOR . $full, $report);
			return false;
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