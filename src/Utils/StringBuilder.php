<?php
	namespace KrameWork\Utils;

	/**
	 * Class StringBuilder
	 * Fluent API String Builder.
	 *
	 * @package KrameWork\Utils
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class StringBuilder
	{
		/**
		 * StringBuilder constructor.
		 * Accepts variable arguments of strings, arrays and objects.
		 *
		 * @api
		 */
		public function __construct() {
			$this->clear();
			$this->append(func_get_args());
		}

		/**
		 * Append string(s) to the builder.
		 * Accepts variable arguments of strings, arrays or objects.
		 *
		 * @api
		 * @return StringBuilder
		 */
		public function append():StringBuilder {
			foreach (func_get_args() as $arg) {
				if (is_array($arg)) {
					foreach ($arg as $subArg)
						$this->append($subArg);
				} else {
					$this->data .= strval($arg);
				}
			}
			return $this;
		}

		/**
		 * Append a formatted string to the builder.
		 *
		 * @api
		 * @param string $format Format pattern.
		 * @return StringBuilder
		 */
		public function appendf(string $format):StringBuilder {
			return $this->append(\call_user_func_array("sprintf", func_get_args()));
		}

		/**
		 * Prepend string(s) to the builder.
		 * Accepts variable arguments of strings, arrays or objects.
		 *
		 * @api
		 * @return StringBuilder
		 */
		public function prepend():StringBuilder {
			foreach (func_get_args() as $arg) {
				if (is_array($arg)) {
					foreach ($arg as $subArg)
						$this->prepend($subArg);
				} else {
					$this->data = strval($arg) . $this->data;
				}
			}
			return $this;
		}

		/**
		 * Prepend a formatted string to the builder.
		 *
		 * @api
		 * @param string $format Format pattern.
		 * @return StringBuilder
		 */
		public function prependf(string $format):StringBuilder {
			return $this->prepend(\call_user_func_array("sprintf", func_get_args()));
		}

		/**
		 * Append/prepend a string $count amount of times.
		 *
		 * @api
		 * @param mixed $input Object that can be cast to a string.
		 * @param int $count How many times to append/prepend the string.
		 * @param bool $append True = Append, False = Prepend.
		 * @return StringBuilder
		 */
		public function repeat($input, $count = 1, bool $append = true):StringBuilder {
			$line = str_repeat(strval($input), $count);
			$append ? $this->append($line) : $this->prepend($line);

			return $this;
		}

		/**
		 * Return the compiled result of the string builder.
		 *
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString() {
			return $this->data;
		}


		/**
		 * Clear any input data to the string builder.
		 *
		 * @api
		 * @return StringBuilder
		 */
		public function clear():StringBuilder {
			$this->data = "";
			return $this;
		}

		/**
		 * @var string
		 */
		private $data;
	}