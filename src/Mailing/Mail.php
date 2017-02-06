<?php
	namespace KrameWork\Mailing;

	use KrameWork\Storage\File;
	use KrameWork\Utils\StringBuilder;
	require_once(__DIR__ . "/../Utils/StringBuilder.php");

	class InvalidRecipientException extends \Exception {}
	class ExcessiveSubjectLengthException extends \Exception {}
	class MissingSubjectException extends \Exception {}
	class MissingSenderException extends \Exception {}
	class AttachmentNotFoundException extends \Exception {}

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
		 * @param bool $containsHTML Send message as HTML.
		 */
		public function __construct(bool $containsHTML = false) {
			$this->recipients = [];
			$this->headers = [];
			$this->files = [];

			$this->containsHTML = $containsHTML;
			$this->addHeader("MIME-Version", "1.0");
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
				$this->recipients[strval($recipient)] = true;
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
				if (array_key_exists($recipient, $this->recipients))
					unset($this->recipients[$recipient]);
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
		 * Attach a file to be sent with this mail.
		 *
		 * @api
		 * @param string $path Path of the file to attach.
		 * @return Mail
		 * @throws AttachmentNotFoundException
		 */
		public function attachFile(string $path):Mail {
			if (!file_exists($path))
				throw new AttachmentNotFoundException("Unable to locate attachment: " . $path);

			if (!is_file($path))
				throw new AttachmentNotFoundException("Not a valid attachment: " . $path);

			$this->files[$path] = true;
			return $this;
		}

		/**
		 * Remove an attached file from this mail object.
		 *
		 * @api
		 * @param string $path Path of the file to remove.
		 * @return Mail
		 */
		public function removeFile(string $path):Mail {
			if (array_key_exists($path, $this->files))
				unset($this->files[$path]);

			return $this;
		}

		/**
		 * Remove all files attached to this mail object.
		 *
		 * @api
		 * @return Mail
		 */
		public function clearFiles():Mail {
			$this->files = [];
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

			$headers = $this->headers;
			$body = chunk_split(base64_encode($this->body), "70", "\r\n");

			$cBody = new StringBuilder();
			$contentType = sprintf("text/%s; charset=UTF-8", $this->containsHTML ? "html" : "plain");

			// Compile body.
			if (count($this->files)) {
				require_once(__DIR__ . "/../Storage/File.php");

				$bound = sprintf("=__%s__=", md5(time()));
				$headers["Content-Type"] = sprintf("multipart/mixed; boundary=\"%s\"", $bound);

				$cBody->appendf("--%s\n", $bound);
				$cBody->appendf("Content-Type: %s\n", $contentType);
				$cBody->append("Content-Transfer-Encoding: base64\n");
				$cBody->append("Content-Disposition: inline\n\n");
				$cBody->appendf("%s\n\n", $body);

				foreach (array_keys($this->files) as $file) {
					if (!($file instanceof File))
						$file = new File($file);

					if (!$file->isValid())
						throw new AttachmentNotFoundException("Unable to attach file: " . $file->getName());

					$cBody->appendf("--%s\n", $bound);
					$cBody->appendf("Content-Type: %s; name=\"%s\"\n", $file->getFileType(), $file->getName());
					$cBody->appendf("Content-Disposition: attachment; filename=\"%s\"\n", $file->getName());
					$cBody->append("Content-Transfer-Encoding: base64\n");
					$cBody->append(chunk_split($file->getBase64Data(true), 76, "\n"))->append("\n\n");
				}

				$cBody->appendf("--%s--\n\n", $bound);
			} else {
				$headers["Content-Type"] = $contentType;
				$headers["Content-Transfer-Encoding"] = "base64";
				$headers["Content-Disposition"] = "inline";

				$cBody->append($body);
			}

			// Compile headers.
			$cHeaders = [];
			foreach ($headers as $name => $value)
				$cHeaders[] = $name . ": " . $value;

			$cHeaders = implode("\n", $cHeaders);

			// Compile recipients.
			$cRecipients = implode(",", array_keys($this->recipients));

			// Compile subject.
			$cSubject = sprintf("=?UTF-8?B?%s?=", base64_encode($this->subject));

			mail($cRecipients, $cSubject, $cBody, $cHeaders);
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

		/**
		 * @var array
		 */
		private $files;

		/**
		 * @var bool
		 */
		private $containsHTML;
	}