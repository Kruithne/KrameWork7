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
	require_once(__DIR__ . "/../Utils/StringBuilder.php");

	interface IMailPart {
		/**
		 * Compile this multipart block into the given StringBuilder.
		 *
		 * @api
		 * @param StringBuilder|null $builder Compilation target.
		 * @param IMailPart|null $parent Parent multipart block.
		 * @return StringBuilder|null
		 */
		public function compile(StringBuilder $builder, IMailPart $parent);
	}

	class InvalidParentException extends \Exception {}

	/**
	 * Class MailMultipart
	 * MIME Multipart Boundary Block
	 *
	 * @package KrameWork\Mailing
	 * @author Kruithne (kruithne@gmail.com)
	 */
	class MailMultipart implements IMailPart
	{
		/**
		 * MailMultipart constructor.
		 *
		 * @api
		 * @param string $type Multipart type, such as 'mixed' or 'alternative'.
		 * @param MailMultipart $parent Parent of this multipart block.
		 */
		public function __construct(string $type, MailMultipart $parent = null) {
			$this->parts = [];
			$this->type = $type;
			$this->boundaryID = uniqid('==_mimepart_' . self::$id++);

			if ($parent)
				$parent->add($this);
		}

		/**
		 * Get the boundary ID for this multipart block.
		 *
		 * @api
		 * @return string
		 */
		public function getBoundaryID():string {
			return $this->boundaryID;
		}

		/**
		 * Add another multipart block to this one.
		 *
		 * @api
		 * @param IMailPart $part Block to add to this.
		 */
		public function add(IMailPart $part) {
			$this->parts[] = $part;
		}

		/**
		 * Get the content-type of this block, with trailing boundary ID.
		 *
		 * @api
		 * @return string
		 */
		public function getContentType():string {
			return 'multipart/' . $this->type . '; boundary="' . $this->boundaryID . '"';
		}

		/**
		 * Compile this multipart block into the given StringBuilder.
		 *
		 * @api
		 * @param StringBuilder|null $builder Compilation target.
		 * @param IMailPart|null $parent Parent multipart block.
		 * @return StringBuilder|null
		 * @throws InvalidParentException
		 */
		public function compile(StringBuilder $builder = null, IMailPart $parent = null) {
			$builder = $builder ?? new StringBuilder(StringBuilder::LE_UNIX);

			if ($parent) {
				if (!($parent instanceof MailMultipart))
					throw new InvalidParentException('MailMultipart parent needs to be MailMultipart');

				$builder->appendLine('--' . $parent->getBoundaryID());
				$builder->appendLine('Content-Type: ' . $this->getContentType());
			}

			foreach ($this->parts as $part)
				$part->compile($builder, $this);

			if (!$parent)
				$builder->appendLine('--' . $this->boundaryID . '--');

			return $builder;
		}

		/**
		 * @var IMailPart[]
		 */
		private $parts;

		/**
		 * @var string
		 */
		private $type;

		/**
		 * @var string
		 */
		private $boundaryID;

		/**
		 * @var int
		 */
		private static $id = 1;
	}