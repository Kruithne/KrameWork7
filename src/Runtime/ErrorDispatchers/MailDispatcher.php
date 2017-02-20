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

	namespace KrameWork\Runtime\ErrorDispatchers;

	use KrameWork\Mailing\Mail;
	use KrameWork\Runtime\ErrorReports\IErrorReport;

	require_once(__DIR__ . '/../../Mailing/Mail.php');

	/**
	 * Class MailDispatcher
	 * Dispatches error reports via e-mail.
	 *
	 * @package KrameWork\Runtime\ErrorDispatchers
	 * @author Kruithne <kruithne@gmail.com>
	 */
	class MailDispatcher implements IErrorDispatcher
	{
		/**
		 * MailDispatcher constructor.
		 *
		 * @api __construct
		 * @param array $recipients Recipients to receive this report.
		 * @param string $sender Sender e-mail address.
		 * @param string $senderName Name of the e-mail sender.
		 * @param string|array $subject Subject string or callable generator.
		 */
		public function __construct(array $recipients, string $sender, string $senderName = null, $subject = 'Error Report') {
			$this->mail = new Mail();
			$this->mail->to->add($recipients);
			$this->mail->setSender($sender, $senderName, true);

			if (!is_array($subject))
				$this->mail->setSubject($subject);
			else
				$this->subjectGen = $subject;
		}

		/**
		 * Dispatch an error report.
		 *
		 * @api dispatch
		 * @param IErrorReport|string $report Report to dispatch.
		 * @return bool
		 */
		public function dispatch($report):bool {
			if ($this->subjectGen) {
				$subject = call_user_func(count($this->subjectGen) == 1 ? $this->subjectGen[0] : $this->subjectGen);
				if ($report instanceof IErrorReport)
					$subject = $report->getError()->getPrefix() . ' ' . $subject;

				$this->mail->setSubject($subject);
			}

			$this->mail->htmlContent->setContent($report);
			$this->mail->send();
			return false;
		}

		/**
		 * @var Mail
		 */
		protected $mail;

		/**
		 * @var array
		 */
		protected $subjectGen;
	}