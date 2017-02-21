<?php
	namespace KrameWork\Runtime\ErrorFormatters;

	use KrameWork\Reporting\HTMLReport\HTMLReportTemplate;
	use KrameWork\Runtime\ErrorReports\ErrorReport;
	use KrameWork\Runtime\ErrorReports\IErrorReport;
	use KrameWork\Runtime\ErrorTypes\IError;

	require_once(__DIR__ . '/../../Reporting/HTMLReport/HTMLReportTemplate.php');

	class InvalidTemplateFileException extends \Exception {}

	class HTMLErrorFormatter implements IErrorFormatter
	{
		/**
		 * HTMLErrorFormatter constructor.
		 *
		 * @api __construct
		 * @param string|null $template Template file to use.
		 * @throws InvalidTemplateFileException
		 */
		public function __construct($template = null) {
			if ($template === null)
				$template = __DIR__ . '/../../../templates/error_report.php';

			if (!file_exists($template))
				throw new InvalidTemplateFileException('Cannot resolve template file.');

			if (!is_file($template))
				throw new InvalidTemplateFileException('Invalid template file given.');

			$this->template = $template;
		}

		/**
		 * Called just before this report is used.
		 *
		 * @api beginReport
		 */
		public function beginReport() {
			$this->data = ['data' => []];
		}

		/**
		 * Format an error and add it to the report.
		 *
		 * @api handleError
		 * @param IError $error Error which occurred.
		 */
		public function reportError(IError $error) {
			$this->error = $error;
			$this->data['prefix'] = $error->getPrefix();
			$this->data['name'] = $error->getName();
			$this->data['message'] = $error->getMessage();
			$this->data['timestamp'] = time();
			$this->data['occurred'] = date(DATE_RFC822);
			$this->data['file'] = $error->getFile();
			$this->data['line'] = $error->getLine();
			$this->data['trace'] = $error->getTrace();
		}

		/**
		 * Format an array and add it to the report.
		 *
		 * @api formatArray
		 * @param string $name Name for the array.
		 * @param array $arr Array of data.
		 */
		public function reportArray(string $name, array $arr) {
			$this->data['data'][$name] = $arr;
		}

		/**
		 * Format a data string and add it to the report.
		 *
		 * @api reportString
		 * @param string $name Name of the data string.
		 * @param string $str Data string.
		 */
		public function reportString(string $name, string $str) {
			$this->data['data'][$name] = $str;
		}

		/**
		 * Generate a report.
		 *
		 * @api generate
		 * @return IErrorReport
		 */
		public function generate():IErrorReport {
			$template = file_get_contents($this->template);
			$report = new HTMLReportTemplate($template);

			foreach ($this->data as $key => $value) {
				if ($key == 'data')
					continue;

				$report->$key = $value;
			}

			return new ErrorReport($this->error, 'text/html; charset=utf-8', '.html', $report);
		}

		private function getVariableString($var):string {
			$type = gettype($var);
			if ($type == 'object') {
				$type = get_class($var);
				if (!method_exists($var, '__toString'))
					$var = $type . ' instance';

			} elseif ($type == 'string') {
				$length = \strlen($var);
				$var = "({$length}) \"{$var}\"";
			} elseif ($type == 'array') {
				$var = count($var) . ' items';
			}

			return "({$type}) {$var}";
		}

		/**
		 * @var IError
		 */
		protected $error;

		/**
		 * @var array
		 */
		protected $data;

		/**
		 * @var string
		 */
		protected $template;
	}