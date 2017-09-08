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

	use KrameWork\Utils\StringBuilder;
	require_once(__DIR__ . "/MailMultipart.php");

	/**
	 * Class MailMultipartContent
	 * MIME Multipart Content Block
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class MailMultipartContent implements IMailPart
	{
		/**
		 * MailMultipartContent constructor.
		 *
		 * @api
		 * @param string $type Content-type (with trailing parameters).
		 * @param MailMultipart $parent
		 */
		public function __construct(string $type, MailMultipart $parent = null) {
			$this->headers = [];
			$this->content = '';
			$this->addHeader('Content-Type', $type);

			if ($parent)
				$parent->add($this);
		}

		/**
		 * Set the transfer encoding of this content block.
		 *
		 * @api
		 * @param string $encoding
		 */
		public function setTransferEncoding(string $encoding) {
			$this->addHeader('Content-Transfer-Encoding', $encoding);
		}

		/**
		 * Set the content disposition for this content block.
		 * Examples: inline, attachment
		 *
		 * @api
		 * @param string $disposition
		 */
		public function setContentDisposition(string $disposition) {
			$this->addHeader('Content-Disposition', $disposition);
		}

		/**
		 * Set the attachment/content ID for this block.
		 *
		 * @api
		 * @param string $id
		 */
		public function setAttachmentID(string $id) {
			$this->addHeader('Content-ID', '<' . $id . '>');
			$this->addHeader('X-Attachment-Id', $id);
		}

		/**
		 * Set the content for this multipart content block.
		 *
		 * @api
		 * @param string $content Content for this block.
		 * @param bool $encodeBase64 Encode the content in base64.
		 */
		public function setContent(string $content, bool $encodeBase64 = false) {
			if ($encodeBase64)
				$content = \chunk_split(\base64_encode($content), 70, "\r\n");

			$this->content = $content;
		}

		/**
		 * Add a header for this content block.
		 *
		 * @api
		 * @param string $name Name of the header.
		 * @param string $value Value of the header.
		 */
		public function addHeader(string $name, string $value) {
			$this->headers[$name] = $value;
		}

		/**
		 * Compile this multipart into the given StringBuilder.
		 *
		 * @api
		 * @param StringBuilder $builder Compilation target.
		 * @param IMailPart $parent Parent of the block.
		 * @return StringBuilder|null|void
		 * @throws InvalidParentException
		 */
		public function compile(StringBuilder $builder, IMailPart $parent) {
			if (!($parent instanceof MailMultipart))
				throw new InvalidParentException('MailMultipartContent parent needs to be MailMultipart');

			$builder->appendLine('--' . $parent->getBoundaryID());
			foreach ($this->headers as $name => $value)
				$builder->appendLine($name . ': ' . $value);

			$builder->newLine()->appendLine($this->content);
		}

		/**
		 * @var array
		 */
		private $headers;

		/**
		 * @var string
		 */
		private $content;
	}