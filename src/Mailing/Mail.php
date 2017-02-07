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
	require_once(__DIR__ . '/MailMultipart.php');

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
		 *
		 * @api
		 */
		public function __construct() {
			$this->headers = [];
			$this->files = [];
			$this->recipients = new \ArrayObject([
				'normal' => [], 'bcc' => [], 'cc' => []
			], \ArrayObject::ARRAY_AS_PROPS);

			$this->addHeader('MIME-Version', '1.0');
		}

		/**
		 * Add recipients to the e-mail.
		 * Array input must be in email=>name format (name can be null).
		 *
		 * @api
		 * @param array|string $email Valid RFC 822 e-mail address(es).
		 * @param null|string $name Recipient name.
		 * @param bool $encode Recipient name will be encoded in base64.
		 * @return Mail
		 * @throws InvalidRecipientException
		 */
		public function addRecipient($email, $name = null, $encode = true):Mail {
			$this->addEmail($this->recipients->normal, $email, $name, $encode);
			return $this;
		}

		/**
		 * Add Cc recipients to this e-mail.
		 * Array input must be in email=>name format (name can be null).
		 *
		 * @api
		 * @param array|string $email Valid RFC822 e-mail address(es).
		 * @param null|string $name Recipient name.
		 * @param bool $encode Recipient name will be encoded in base64.
		 * @return Mail
		 * @throws InvalidRecipientException
		 */
		public function addCc($email, $name = null, $encode = true):Mail {
			$this->addEmail($this->recipients->cc, $email, $name, $encode);
			return $this;
		}

		/**
		 * Add Bcc recipients to this e-mail.
		 * Array input must be in email=>name format (name can be null).
		 *
		 * @api
		 * @param array|string $email Valid RFC822 e-mail address(es).
		 * @param null|string $name Recipient name.
		 * @param bool $encode Recipient name will be encoded in base64.
		 * @return Mail
		 * @throws InvalidRecipientException
		 */
		public function addBcc($email, $name = null, $encode = true):Mail {
			$this->addEmail($this->recipients->bcc, $email, $name, $encode);
			return $this;
		}

		/**
		 * Remove a recipient from the e-mail.
		 *
		 * @api
		 * @param string|array $email E-mail address(es) to remove.
		 * @return Mail
		 */
		public function removeRecipient($email):Mail {
			$this->removeEmail($this->recipients->normal, $email);
			return $this;
		}

		/**
		 * Remove a Cc recipient from the e-mail.
		 *
		 * @api
		 * @param string|array $email E-mail address(es) to remove.
		 * @return Mail
		 */
		public function removeCc($email):Mail {
			$this->removeEmail($this->recipients->cc, $email);
			return $this;
		}

		/**
		 * Remove a Bcc recipient from the e-mail.
		 *
		 * @api
		 * @param string|array $email E-mail address(es) to remove.
		 * @return Mail
		 */
		public function removeBcc($email):Mail {
			$this->removeEmail($this->recipients->bcc, $email);
			return $this;
		}

		/**
		 * Clear all recipients from the e-mail.
		 *
		 * @api
		 * @return Mail
		 */
		public function clearRecipients():Mail {
			$this->recipients->normal = [];
			return $this;
		}

		/**
		 * Clear all Bcc recipients from the mail.
		 *
		 * @api
		 * @return Mail
		 */
		public function clearBcc():Mail {
			$this->recipients->bcc = [];
			return $this;
		}

		/**
		 * Clear all Cc recipients from the mail.
		 *
		 * @api
		 * @return Mail
		 */
		public function clearCc():Mail {
			$this->recipients->cc = [];
			return $this;
		}

		/**
		 * Validate and add an e-mail address to a specific stack.
		 * Array input must be in email=>name format (name can be null).
		 *
		 * @internal
		 * @param array $stack Stack to add the e-mail address to.
		 * @param array|string $email Valid RFC 822 e-mail address(es).
		 * @param null|string $name Name of the recipient.
		 * @param bool $encode Recipient name will be encoded in base64.
		 * @throws InvalidRecipientException
		 */
		private function addEmail(&$stack, $email, $name = null, $encode = true) {
			// Array: Treat array as $email => $name key/value pair array.
			if (is_array($email)) {
				foreach ($email as $nodeEmail => $nodeName)
					$this->addEmail($stack, $nodeEmail, $nodeName, $encode);
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
				$stack[strval($email)] = $name;
			}
		}

		/**
		 * Remove a recipient from the e-mail.
		 *
		 * @api
		 * @param array $stack Stack to remove the recipient from.
		 * @param array|string $email E-mail address(es).
		 * @return Mail
		 */
		public function removeEmail(&$stack, $email):Mail {
			// Array: Treat all elements as individual recipients.
			if (is_array($email)) {
				foreach ($email as $node)
					$this->removeEmail($stack, $node);
			} else {
				$email = strval($email); // Ensure we have a string.

				// Delete the recipient from the stack.
				if (array_key_exists($email, $stack))
					unset($stack[$email]);
			}
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

		public function setHTMLBody($content):Mail {
			$this->htmlBody = $content;
			return $this;
		}

		public function setPlainBody($content):Mail {
			$this->plainBody = $content;
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
			else
				$attachment = basename($attachment);

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

			// Compile Body
			$bParent = new MailMultipart('mixed');
			$bBody = new MailMultipart('alternative', $bParent);

			if ($this->plainBody !== null || $this->htmlBody === null)
				$bBody->add($this->compilePlainBody());

			if ($this->htmlBody !== null)
				$bBody->add($this->compileHTMLBody());

			$this->compileAttachments($bParent);

			// Compile headers.
			$cHeaders = ['Content-Type: ' . $bParent->getContentType()];
			foreach ($this->headers as $name => $value)
				$cHeaders[] = $name . ': ' . $value;

			// Compile recipients.
			$cRecipients = $this->compileRecipientStack($this->recipients->normal);
			if (count($this->recipients->bcc))
				$cHeaders[] = 'Bcc: ' . $this->compileRecipientStack($this->recipients->bcc);

			if (count($this->recipients->cc))
				$cHeaders[] = 'Cc: ' . $this->compileRecipientStack($this->recipients->cc);

			// Compile subject.
			$cSubject = '=?UTF-8?B?' . base64_encode($this->subkect ?? 'No Subject') . '?=';

			mail($cRecipients, $cSubject, $bParent->compile(),  implode("\n", $cHeaders));
		}

		/**
		 * Compile a recipient stack into a string.
		 *
		 * @internal
		 * @param array $stack Email=>Name recipient stack.
		 * @return string
		 */
		private function compileRecipientStack($stack):string {
			$result = [];
			foreach ($stack as $email => $name)
				$result[] = '"' . $name . '" <' . $email . '>';

			return implode(',', $result);
		}

		/**
		 * Compile the plain-text body of this e-mail and return it as a content block.
		 *
		 * @internal
		 * @return MailMultipartContent
		 */
		private function compilePlainBody():MailMultipartContent {
			$content = new MailMultipartContent('text/plain; charset=UTF-8');
			$content->setTransferEncoding('7bit');
			$content->setContentDisposition('inline');
			$content->setContent($this->plainBody ?? '', false);
			return $content;
		}

		/**
		 * Compile the HTML body of this email and return it as a content block.
		 *
		 * @internal
		 * @return MailMultipartContent
		 */
		private function compileHTMLBody():MailMultipartContent {
			$content = new MailMultipartContent('text/html; charset=UTF-8');
			$content->setTransferEncoding('base64');
			$content->setContentDisposition('inline');
			$content->setContent($this->htmlBody, true);
			return $content;
		}

		/**
		 * Compile attachments into the given multipart block.
		 *
		 * @internal
		 * @param MailMultipart $container
		 * @throws AttachmentNotFoundException
		 */
		private function compileAttachments(MailMultipart $container) {
			if (count($this->files)) {
				foreach ($this->files as $file) {
					if (!$file->isValid())
						throw new AttachmentNotFoundException('Unable to attach file: ' . $file->getName());

					$bFile = new MailMultipartContent($file->getFileType() . '; name="' . $file->getName() . '"', $container);
					$bFile->setContentDisposition('attachment; filename="' . $file->getName() . '"');
					$bFile->setTransferEncoding('base64');
					$bFile->setContent($file->getData(true), true);
				}
			}
		}

		/**
		 * @var \ArrayObject
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
		private $plainBody;

		/**
		 * @var string
		 */
		private $htmlBody;

		/**
		 * @var File[]
		 */
		private $files;
	}