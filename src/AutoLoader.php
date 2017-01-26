<?php
	namespace KrameWork;

	/**
	 * Class AutoLoader
	 * @package KrameWork
	 * Handles the automatic loading of files based on class initiation.
	 */
	class AutoLoader {
		const RECURSIVE_SOURCING = 0x1;
		const INCLUDE_WORKING_DIRECTORY = 0x2;
		const INCLUDE_KRAMEWORK_DIRECTORY = 0x4;
		const DEFAULT_FLAGS = self::INCLUDE_WORKING_DIRECTORY | self::INCLUDE_KRAMEWORK_DIRECTORY | self::RECURSIVE_SOURCING;

		/**
		 * AutoLoader constructor.
		 * @param string[] $sources List of directories to auto-load from.
		 * @param string[] $extensions Allowed extensions.
		 * @param int $flags Flags to control auto-loading.
		 */
		public function __construct(array $sources = null, array $extensions = null, int $flags = self::DEFAULT_FLAGS) {
			$this->enabled = true;
			$this->sources = [];
			$this->extensions = [];

			// Remove any leading periods from extentions.
			foreach ($extensions ?? ["php"] as $ext)
				$this->extensions[] = ltrim($ext, ".");

			// Pre-compute source paths/maps.
			foreach ($sources ?? [] as $source) {
				if (is_array($source) && count($source) == 2) {
					$real = realpath($source[1]);
					if ($real !== false) {
						$source[1] = $real;

						// Convert namespace separators if needed.
						if (DIRECTORY_SEPARATOR == "/")
							$source[0] = str_replace("\\", DIRECTORY_SEPARATOR, $source[0]);

						$this->sources[] = $source;
					}
				} else if (is_string($source)) {
					$real = realpath($source);
					if ($real !== false)
						$this->sources[] = $real;
				}
			}

			if ($flags & self::INCLUDE_KRAMEWORK_DIRECTORY)
				$this->sources["KrameWork"] = dirname(__FILE__);

			if ($flags & self::INCLUDE_WORKING_DIRECTORY)
				$this->sources[] = getcwd();

			// Register this auto-loader instance with PHP.
			spl_autoload_register([$this, 'loadClass']);
		}

		/**
		 * Attempt to load a given class.
		 * @param $class
		 */
		public function loadClass($class) {
			if (!$this->enabled)
				return;

			$queue = $this->sources;

			while (count($queue)) {
				$directory = array_pop($queue);
				if (is_array($directory)) {
					list($namespace, $path) = $directory;
					$namespaceLen = strlen($namespace);

					if (strncmp($class, $namespace, $namespaceLen) !== 0)
						continue;

					$class = trim(substr($class, $namespaceLen), DIRECTORY_SEPARATOR);
					$directory = $path;
				}

				foreach ($this->extensions as $ext) {
					$file = $directory . DIRECTORY_SEPARATOR . $class . '.' . $ext;
					if (file_exists($file))
						require_once($file);
				}

				if ($this->flags & self::RECURSIVE_SOURCING) {
					foreach (scandir($directory) as $node) {
						if ($node == "." || $node == "..")
							continue;

						$path = $directory . DIRECTORY_SEPARATOR . $node;
						if (is_dir($path))
							array_unshift($queue, $path);
					}
				}
			}
		}

		/**
		 * Disable this auto-loader.
		 */
		public function disable() {
			$this->enabled = false;
		}

		/**
		 * Enable this auto-loader.
		 */
		public function enable() {
			$this->enabled = true;
		}

		/**
		 * @var string[]
		 */
		private $sources;

		/**
		 * @var string[]
		 */
		private $extensions;

		/**
		 * @var int
		 */
		private $flags;

		/**
		 * @var bool
		 */
		private $enabled;
	}