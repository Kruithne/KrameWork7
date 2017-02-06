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

	use KrameWork\Storage\File;
	require_once(__DIR__ . '/../Storage/File.php');

	use KrameWork\Utils\StringBuilder;
	require_once(__DIR__ . '/../Utils/StringBuilder.php');

	class InvalidRecipientException extends \Exception {}
	class ExcessiveSubjectLengthException extends \Exception {}
	class MissingSenderException extends \Exception {}
	class AttachmentNotFoundException extends \Exception {}
	class DuplicateAttachmentException extends \Exception {}

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
			$this->addHeader('MIME-Version', '1.0');
		}

		/**
		 * Add a recipient to the e-mail.
		 * Array input must be in email=>name format (name can be null).
		 *
		 * @api
		 * @param array|string $email Valid RFC 822 e-mail address(es).
		 * @param null $name Name of the recipient.
		 * @param bool $encode Recipient name will be encoded in base64.
		 * @return Mail
		 * @throws InvalidRecipientException
		 */
		public function addRecipient($email, $name = null, $encode = true):Mail {
			// Array: Treat array as $email => $name key/value pair array.
			if (is_array($email)) {
				foreach ($email as $nodeEmail => $nodeName)
					$this->addRecipient($nodeEmail, $nodeName, $encode);
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
		 * Remove a recipient from the e-mail.
		 *
		 * @api
		 * @param array|string $email E-mail address(es).
		 * @return Mail
		 */
		public function removeRecipient($email):Mail {
			// Array: Treat all elements as individual recipients.
			if (is_array($email)) {
				foreach ($email as $node)
					$this->removeRecipient($node);
			} else {
				$email = strval($email); // Ensure we have a string.

				// Delete the recipient from the stack.
				if (array_key_exists($email, $this->recipients))
					unset($this->recipients[$email]);
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
				throw new ExcessiveSubjectLengthException('Subject exceeds RFC 2822 length limit.');

			$this->subject = $subject;
			return $this;
		}

		/**
		 * Set the sender of this e-mail.
		 *
		 * @api
		 * @param string $sender E-mail address that sent this mail.
		 * @param bool $generateMessageID Generate a Message-Id header using this sender.
		 * @return Mail
		 */
		public function setSender(string $sender, bool $generateMessageID = false):Mail {
			$this->addHeader('From', $sender);

			if ($generateMessageID) {
				$domain = explode('@', $sender);
				$this->addHeader('Message-Id', '<' . uniqid(rand()) . '@' . $domain[count($domain) - 1] . '>');
			}

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
		 * @param string|File $attachment Attachment.
		 * @return Mail
		 * @throws AttachmentNotFoundException
		 * @throws DuplicateAttachmentException
		 */
		public function attachFile($attachment):Mail {
			if (!($attachment instanceof File)) {
				$attachment = new File($attachment, false, false);
				if (!$attachment->isValid())
					throw new AttachmentNotFoundException('Cannot attach: ' . $attachment->getName());
			}

			if (array_key_exists($attachment->getName(), $this->files))
				throw new DuplicateAttachmentException('Attachment already exists: ' . $attachment->getName());

			$this->files[$attachment->getName()] = $attachment;
			return $this;
		}

		/**
		 * Remove an attached file from this mail object.
		 *
		 * @api
		 * @param string|File $attachment Attachment to remove.
		 * @return Mail
		 */
		public function removeFile($attachment):Mail {
			if ($attachment instanceof File)
				$attachment = $attachment->getName();

			if (array_key_exists($attachment, $this->files))
				unset($this->files[$attachment]);

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
		 */
		public function send() {
			if (!count($this->recipients))
				throw new InvalidRecipientException('Cannot send mail without recipients.');

			if (!array_key_exists('From', $this->headers))
				throw new MissingSenderException('Cannot send mail without a sender.');

			$headers = $this->headers;
			$body = chunk_split(base64_encode($this->body ?? ''), 70, "\r\n");

			$cBody = new StringBuilder();
			$contentType = 'text/' . ($this->containsHTML ? 'html' : 'plain') . '; charset=UTF-8';

			// Compile body.
			if (count($this->files)) {
				$bound = '=__' . md5(time()) . '__=';
				$headers['Content-Type'] = 'multipart/mixed; boundary="' . $bound. '"';

				$cBody->append('--', $bound, "\n");
				$cBody->append('Content-Type: ', $contentType, "\n");
				$cBody->append('Content-Transfer-Encoding: base64', "\n");
				$cBody->append('Content-Disposition: inline', "\n\n");
				$cBody->append($body, "\n\n");

				/**
				 * @var File $file
				 */
				foreach ($this->files as $file) {
					if (!$file->isValid())
						throw new AttachmentNotFoundException('Unable to attach file: ' . $file->getName());

					$cBody->append('--', $bound, "\n");
					$cBody->append('Content-Type: ', $file->getFileType(), '; name="', $file->getName(), "\"\n");
					$cBody->append('Content-Disposition: attachment; filename"', $file->getName(), "\"\n");
					$cBody->append('Content-Transfer-Encoding: base64', "\n");
					$cBody->append(chunk_split($file->getBase64Data(true), 76, "\n"), "\n\n");
				}

				$cBody->append('--', $bound, "--\n\n");
			} else {
				$headers['Content-Type'] = $contentType;
				$headers['Content-Transfer-Encoding'] = 'base64';
				$headers['Content-Disposition'] = 'inline';

				$cBody->append($body);
			}

			// Compile headers.
			$cHeaders = [];
			foreach ($headers as $name => $value)
				$cHeaders[] = $name . ': ' . $value;

			$cHeaders = implode("\n", $cHeaders);

			// Compile recipients.
			$cRecipients = [];
			foreach ($this->recipients as $email => $name)
				$cRecipients[] = '"' . $name . '" <' . $email . '>';

			$cRecipients = implode(",", $cRecipients);

			// Compile subject.
			$cSubject = '=?UTF-8?B?' . base64_encode($this->subkect ?? 'No Subject') . '?=';

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