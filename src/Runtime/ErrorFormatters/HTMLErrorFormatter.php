<?php
	namespace KrameWork\Runtime\ErrorFormatters;

	use KrameWork\Reporting\HTMLReport\HTMLReportTemplate;
	use KrameWork\Runtime\ErrorReports\ErrorReport;
	use KrameWork\Runtime\ErrorReports\IErrorReport;
	use KrameWork\Runtime\ErrorTypes\IError;

	require_once(__DIR__ . '/../../Reporting/HTMLReport/HTMLReportTemplate.php');

	class HTMLErrorFormatter implements IErrorFormatter
	{
		/**
		 * HTMLErrorFormatter constructor.
		 *
		 * @api __construct
		 * @param string|null $templatePath Path to HTML template.
		 */
		public function __construct($templatePath = null) {
			$this->templatePath = $templatePath;
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
			$report = new HTMLReportTemplate($this->getTemplate());

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
		 * Get the template for this report.
		 *
		 * @internal
		 * @return string
		 */
		private function getTemplate():string {
			if ($this->template !== null)
				return $this->template;

			if ($this->templatePath !== null && $this->loadTemplateFile($this->templatePath))
				return $this->template;

			if ($this->loadTemplateFile(__DIR__ . '/../../../templates/error_report.php'))
				return $this->template;

			return '';
		}

		/**
		 * Attempt to load template data from a file.
		 *
		 * @internal
		 * @param string $file File to load the template from.
		 * @return bool
		 */
		private function loadTemplateFile(string $file):bool {
			if (file_exists($file) && is_file($file)) {
				$data = file_get_contents($file);
				if ($data !== false) {
					$this->template = $data;
					return true;
				}
			}
			return false;
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

		/**
		 * @var string
		 */
		protected $templatePath;
	}