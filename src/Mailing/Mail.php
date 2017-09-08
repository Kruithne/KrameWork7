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

	require_once(__DIR__ . '/AttachmentFile.php');
	require_once(__DIR__ . '/MailMultipart.php');
	require_once(__DIR__ . '/MailMultipartContent.php');
	require_once(__DIR__ . '/RecipientCollection.php');
	require_once(__DIR__ . '/EncodedContent.php');

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
		 * @api __construct
		 */
		public function __construct() {
			$this->headers = [];
			$this->files = [];

			$this->to = new RecipientCollection();
			$this->cc = new RecipientCollection();
			$this->bcc = new RecipientCollection();

			$this->plainContent = new EncodedContent(EncodedContent::E_7BIT);
			$this->htmlContent = new EncodedContent(EncodedContent::E_QUOTED_PRINTABLE);

			$this->addHeader('MIME-Version', '1.0');
		}

		/**
		 * Set the subject of this e-mail.
		 * Strict limit of 998 characters, but more than 78 is considered bad.
		 *
		 * @api setSubject
		 * @param string $subject Subject of the e-mail.
		 * @return Mail
		 * @throws ExcessiveSubjectLengthException
		 */
		public function setSubject(string $subject):Mail {
			if (\strlen($subject) > 998)
				throw new ExcessiveSubjectLengthException('Subject exceeds RFC 2822 length limit.');

			$this->subject = $subject;
			return $this;
		}

		/**
		 * Set the sender of this e-mail.
		 *
		 * @api setSender
		 * @param string $senderEmail E-mail address of the sender.
		 * @param string $senderName Name of the sender.
		 * @param bool $generateMessageID Generate a Message-Id header using this sender.
		 * @return Mail
		 */
		public function setSender(string $senderEmail, $senderName = null, bool $generateMessageID = false):Mail {
			// Store e-mail address for setting envelope sender later.
			$this->sender = $senderEmail;

			// Generate message-ID if requested.
			if ($generateMessageID) {
				$domain = \explode('@', $senderEmail);
				$this->addHeader('Message-Id', '<' . \uniqid(\rand()) . '@' . $domain[\count($domain) - 1] . '>');
			}

			// Store as header.
			$sender = $senderEmail;
			if ($senderName !== null)
				$sender = '"' . $senderName . '" <' . $sender . '>';

			$this->addHeader('From', $sender);

			return $this;
		}

		/**
		 * Add a header to this e-mail.
		 *
		 * @api addHeader
		 * @param string $name Header name.
		 * @param string $value Header value.
		 * @return Mail
		 */
		public function addHeader(string $name, string $value):Mail {
			$this->headers[$name] = trim($value);
			return $this;
		}

		/**
		 * Attach a file to be sent with this mail.
		 *
		 * @api attachFile
		 * @param string|File $attachment Attachment.
		 * @param bool $inline Is the attachment an inline embed?
		 * @return Mail
		 * @throws AttachmentNotFoundException
		 * @throws DuplicateAttachmentException
		 */
		public function attachFile($attachment, bool $inline = false):Mail {
			if (!($attachment instanceof AttachmentFile)) {
				$attachment = new AttachmentFile($attachment, false, false);
				if (!$attachment->isValid())
					throw new AttachmentNotFoundException('Cannot attach: ' . $attachment->getName());
			}

			if (\array_key_exists($attachment->getName(), $this->files))
				throw new DuplicateAttachmentException('Attachment already exists: ' . $attachment->getName());

			$attachment->setInline($inline);
			$this->files[$attachment->getName()] = $attachment;
			return $this;
		}

		/**
		 * Remove an attached file from this mail object.
		 *
		 * @api removeFile
		 * @param string|AttachmentFile $attachment Attachment to remove.
		 * @return Mail
		 */
		public function removeFile($attachment):Mail {
			if ($attachment instanceof AttachmentFile)
				$attachment = $attachment->getName();
			else
				$attachment = \basename($attachment);

			if (\array_key_exists($attachment, $this->files))
				unset($this->files[$attachment]);

			return $this;
		}

		/**
		 * Remove all files attached to this mail object.
		 *
		 * @api clearFiles
		 * @return Mail
		 */
		public function clearFiles():Mail {
			$this->files = [];
			return $this;
		}

		/**
		 * Send this mail!
		 *
		 * @api send
		 * @throws InvalidRecipientException
		 * @throws MissingSenderException
		 */
		public function send() {
			if ($this->to->isEmpty())
				throw new InvalidRecipientException('Cannot send mail without recipients.');

			if (!\array_key_exists('From', $this->headers))
				throw new MissingSenderException('Cannot send mail without a sender.');

			// Compile Body
			$bParent = new MailMultipart('mixed');
			$bBody = new MailMultipart('alternative', $bParent);

			if ($this->plainContent->hasContent())
				$bBody->add($this->compilePlainBody());

			if ($this->htmlContent->hasContent())
				$bBody->add($this->compileHTMLBody());

			$this->compileAttachments($bParent);

			// Compile headers.
			$cHeaders = ['Content-Type: ' . $bParent->getContentType()];
			foreach ($this->headers as $name => $value)
				$cHeaders[] = $name . ': ' . $value;

			// Compile recipients.
			if (!$this->bcc->isEmpty())
				$cHeaders[] = 'Bcc: ' . $this->bcc;

			if (!$this->cc->isEmpty())
				$cHeaders[] = 'Cc: ' . $this->cc;

			// Compile subject.
			$cSubject = '=?UTF-8?B?' . \base64_encode($this->subject ?? 'No Subject') . '?=';

			mail($this->to, $cSubject, $bParent->compile(),  \implode("\n", $cHeaders), '-f ' . $this->sender);
		}

		/**
		 * Compile the plain-text body of this e-mail and return it as a content block.
		 *
		 * @internal
		 * @return MailMultipartContent
		 */
		private function compilePlainBody():MailMultipartContent {
			$content = new MailMultipartContent('text/plain; charset=UTF-8');
			$content->setTransferEncoding($this->plainContent->getEncoding());
			$content->setContentDisposition('inline');
			$content->setContent($this->plainContent, false);
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
			$content->setTransferEncoding($this->htmlContent->getEncoding());
			$content->setContentDisposition('inline');
			$content->setContent($this->htmlContent, false);
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
			if ($this->files) {
				foreach ($this->files as $file) {
					if (!$file->isValid())
						throw new AttachmentNotFoundException('Unable to attach file: ' . $file->getName());

					$bFile = new MailMultipartContent($file->getFileType() . '; name="' . $file->getName() . '"', $container);
					$bFile->setContentDisposition(($file->isInline() ? 'inline' : 'attachment') . '; filename="' . $file->getName() . '"');
					$bFile->setTransferEncoding('base64');
					$bFile->setContent($file->getData(true), true);

					if ($file->isInline())
						$bFile->setAttachmentID($file->getContentID());
				}
			}
		}

		/**
		 * @var RecipientCollection
		 */
		public $to;

		/**
		 * @var RecipientCollection
		 */
		public $cc;

		/**
		 * @var RecipientCollection
		 */
		public $bcc;

		/**
		 * @var string
		 */
		private $sender;

		/**
		 * @var EncodedContent
		 */
		public $plainContent;

		/**
		 * @var EncodedContent
		 */
		public $htmlContent;

		/**
		 * @var string
		 */
		private $subject;

		/**
		 * @var array
		 */
		private $headers;

		/**
		 * @var AttachmentFile[]
		 */
		private $files;
	}