<?php
	namespace KrameWork\Mailing;

	class InvalidRecipientException extends \Exception {}
	class ExcessiveSubjectLengthException extends \Exception {}
	class MissingSubjectException extends \Exception {}
	class MissingSenderException extends \Exception {}

	/**
	 * Class Mail
	 * Fluent API instance-based mail object.
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class Mail
	{
		/**
		 * Mail constructor.
		 */
		public function __construct() {
			$this->clearRecipients();
			$this->headers = [];
		}

		/**
		 * Add a recipient to the e-mail.
		 *
		 * @api
		 * @param array|string $recipient Valid RFC 822 e-mail address(es).
		 * @return Mail
		 * @throws InvalidRecipientException
		 */
		public function addRecipient($recipient):Mail {
			// Array: Treat all elements as individual recipients.
			if (is_array($recipient)) {
				foreach ($recipient as $node)
					$this->addRecipient($node);
			} else {
				// filter_var provides RFC 822 validation, mail() requires
				// RFC 2822 compliance, but this should be enough to catch
				// most issues.
				$validate = filter_var($recipient, FILTER_VALIDATE_EMAIL);
				if ($validate === false)
					throw new InvalidRecipientException("Invalid e-mail address (RFC 822)");

				// Add the recipient to the stack.
				$this->recipients[] = strval($recipient);
			}
			return $this;
		}

		/**
		 * Remove a recipient from the e-mail.
		 *
		 * @api
		 * @param array|string $recipient E-mail address(es).
		 * @return Mail
		 */
		public function removeRecipient($recipient):Mail {
			// Array: Treat all elements as individual recipients.
			if (is_array($recipient)) {
				foreach ($recipient as $node)
					$this->removeRecipient($node);
			} else {
				$recipient = strval($recipient); // Ensure we have a string.

				// Delete the recipient from the stack.
				if (($index = array_search($recipient, $this->recipients)) !== false)
					unset($this->recipients[$index]);
			}
			return $this;
		}

		/**
		 * Clear all recipients from the e-mail.
		 *
		 * @api
		 * @return Mail
		 */
		public function clearRecipients():Mail {
			$this->recipients = [];
			return $this;
		}

		/**
		 * Set the subject of this e-mail.
		 * Strict limit of 998 characters, but more than 78 is considered bad.
		 *
		 * @api
		 * @param string $subject
		 * @return Mail
		 * @throws ExcessiveSubjectLengthException
		 */
		public function setSubject(string $subject):Mail {
			if (strlen($subject) > 998)
				throw new ExcessiveSubjectLengthException("Subject exceeds RFC 2822 length limit.");

			$this->subject = $subject;
			return $this;
		}

		/**
		 * Set the sender of this e-mail.
		 *
		 * @api
		 * @param string $sender
		 * @return Mail
		 */
		public function setSender(string $sender):Mail {
			$this->addHeader("From", $sender);
			return $this;
		}

		/**
		 * Add a header to this e-mail.
		 *
		 * @api
		 * @param string $name Header name.
		 * @param string $value Header value.
		 * @return Mail
		 */
		public function addHeader(string $name, string $value):Mail {
			$this->headers[$name] = trim($value);
			return $this;
		}

		/**
		 * Set the body of this e-mail.
		 *
		 * @param string $body
		 * @return Mail
		 */
		public function setBody($body):Mail {
			$this->body = strval($body);
			return $this;
		}

		/**
		 * Send this mail!
		 *
		 * @api
		 * @throws InvalidRecipientException
		 * @throws MissingSenderException
		 * @throws MissingSubjectException
		 */
		public function send() {
			if (!count($this->recipients))
				throw new InvalidRecipientException("Cannot send mail without recipients.");

			if ($this->subject === null)
				throw new MissingSubjectException("Cannot send mail without a subject.");

			if (!array_key_exists("From", $this->headers))
				throw new MissingSenderException("Cannot send mail without a sender.");

			// Compile headers.
			$headers = [];
			foreach ($this->headers as $name => $value)
				$headers[] = $name . ": " . $value;

			// Compile recipients.
			$recipients = implode(",", $this->recipients);

			// Parse body.
			$body = str_replace("\r\n", "__CRLF__", $this->body);
			$body = str_replace("\n", "\r\n", $body);
			$body = str_replace("__CRLF__", "\r\n", $body);
			$body = wordwrap($body, 70, "\r\n");

			mail($recipients, $this->subject, $body, $headers);
		}

		/**
		 * @var array
		 */
		private $recipients;

		/**
		 * @var string
		 */
		private $subject;

		/**
		 * @var array
		 */
		private $headers;

		/**
		 * @var string
		 */
		private $body;
	}