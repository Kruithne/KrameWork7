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

	namespace KrameWork\Mailing;

	class InvalidRecipientException extends \Exception {}

	/**
	 * Class RecipientCollection
	 * Fluent API mail recipient container.
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruitne@gmail.com)
	 */
	class RecipientCollection
	{
		public function __construct() {
			$this->recipients = [];
		}

		/**
		 * Add a recipient (or multiple) to this collection.
		 * Arrays must be in an email=>name format (name can be null).
		 *
		 * @api
		 * @param string|array $email RFC 822 compliant e-mail address(es).
		 * @param null|string $name Name of the e-mail recipient.
		 * @param bool $encode Encode the e-mail recipient name.
		 * @return RecipientCollection
		 * @throws InvalidRecipientException
		 */
		public function add($email, $name = null, $encode = true):RecipientCollection {
			// Array: Treat array as $email => $name key/value pair array.
			if (is_array($email)) {
				foreach ($email as $nodeEmail => $nodeName)
					$this->add($nodeEmail, $nodeName, $encode);
			} else {
				// filter_var provides RFC 822 validation, mail() requires
				// RFC 2822 compliance, but this should be enough to catch
				// most issues.
				$validate = filter_var($email, FILTER_VALIDATE_EMAIL);
				if ($validate === false)
					throw new InvalidRecipientException('Invalid e-mail address (RFC 822)');

				// Encode name.
				if ($encode)
					$name = '=?UTF-8?B?' . base64_encode($name) . '?=';

				// Add the recipient to the stack.
				$this->recipients[strval($email)] = $name;
			}
			return $this;
		}

		/**
		 * Remove a recipient (or multiple) from this collection.
		 *
		 * @api
		 * @param string|array $email E-mail address(es) to remove.
		 * @return RecipientCollection
		 */
		public function remove($email):RecipientCollection {
			// Array: Treat all elements as individual recipients.
			if (is_array($email)) {
				foreach ($email as $node)
					$this->remove($node);
			} else {
				$email = strval($email); // Ensure we have a string.

				// Delete the recipient from the stack.
				if (array_key_exists($email, $this->recipients))
					unset($this->recipients[$email]);
			}

			return $this;
		}

		/**
		 * Clear all recipients from this collection.
		 *
		 * @api
		 * @return RecipientCollection
		 */
		public function clear():RecipientCollection {
			$this->recipients = [];
			return $this;
		}

		/**
		 * Check if this collection contains any recipients.
		 *
		 * @api
		 * @return bool
		 */
		public function isEmpty():bool {
			return count($this->recipients) == 0;
		}

		/**
		 * Compile the recipients into a comma-separated string.
		 *
		 * @api
		 * @return string
		 */
		public function __toString() {
			$result = [];
			foreach ($this->recipients as $email => $name)
				$result[] = '"' . $name . '" <' . $email . '>';

			return implode(',', $result);
		}

		/**
		 * @var array
		 */
		private $recipients;
	}