<?php
	namespace KrameWork\Runtime\ErrorFormatters;

	use KrameWork\Reporting\HTMLReport\HTMLReportTemplate;
	use KrameWork\Runtime\ErrorReports\ErrorReport;
	use KrameWork\Runtime\ErrorReports\IErrorReport;
	use KrameWork\Runtime\ErrorTypes\IError;
	use Kramework\Utils\StringUtil;

	require_once(__DIR__ . '/../../Reporting/HTMLReport/HTMLReportTemplate.php');
	require_once(__DIR__ . '/../../Utils/StringUtil.php');

	class HTMLErrorFormatter implements IErrorFormatter
	{
		/**
		 * HTMLErrorFormatter constructor.
		 *
		 * @api __construct
		 * @param string|null $templatePath Path to HTML template.
		 * @param string|null $cssPath CSS file to be prepended to the report.
		 */
		public function __construct($templatePath = null, $cssPath = null) {
			$this->templatePath = $templatePath;
			$this->cssPath = $cssPath;
		}

		/**
		 * Called just before this report is used.
		 *
		 * @api beginReport
		 */
		public function beginReport() {
			$this->data = [];
			$this->basicData = [];
			$this->trace = [];
		}

		/**
		 * Format an error and add it to the report.
		 *
		 * @api handleError
		 * @param IError $error Error which occurred.
		 */
		public function reportError(IError $error) {
			$this->error = $error;
			$this->basicData = [
				'server' => php_uname(),
				'timestamp' => time(),
				'name' => $error->getName(),
				'file' => $error->getFile(),
				'line' => $error->getLine(),
				'occurred' => date(DATE_RFC822),
				'prefix' => $error->getPrefix(),
				'message' => $error->getMessage()
			];
		}

		/**
		 * Format an array and add it to the report.
		 *
		 * @api formatArray
		 * @param string $name Name for the array.
		 * @param array $arr Array of data.
		 */
		public function reportArray(string $name, array $arr) {
			$this->data[$name] = $arr;
		}

		/**
		 * Format a data string and add it to the report.
		 *
		 * @api reportString
		 * @param string $name Name of the data string.
		 * @param string $str Data string.
		 */
		public function reportString(string $name, string $str) {
			$this->data[$name] = $str;
		}

		/**
		 * Format a stacktrace and add it to the report.
		 *
		 * @api reportStacktrace
		 * @param array $trace Stacktrace.
		 */
		public function reportStacktrace(array $trace) {
			$this->trace = $trace;
		}

		/**
		 * Generate a report.
		 *
		 * @api generate
		 * @return IErrorReport
		 */
		public function generate():IErrorReport {
			$report = new HTMLReportTemplate($this->getTemplate());

			// Handle basic data.
			foreach ($this->basicData as $key => $value)
				$report->$key = $value;

			// Handle stacktrace.
			$traceSection = $report->getSection('TRACE_FRAME');
			if ($traceSection->isValid()) {
				$index = 0;

				foreach ($this->trace as $traceFrame) {
					$args = [];
					foreach ($traceFrame['args'] ?? [] as $key => $arg)
						$args[] = htmlentities(StringUtil::variableAsString($arg));

					$frame = $traceSection->createFrame();
					$frame->index = $index++;
					$frame->file = $traceFrame['file'] ?? 'interpreter';
					$frame->line = $traceFrame['line'] ?? '?';
					$frame->class = $traceFrame['class'] ?? '';
					$frame->type = $traceFrame['type'] ?? '';
					$frame->function = $traceFrame['function'] ?? '';
					$frame->args = implode(', ', $args);
				}
			}

			// Handle data sections.
			$stringSection = $report->getSection('DATA_SET_STRING');
			$arraySection = $report->getSection('DATA_SET_ARRAY');

			foreach ($this->data as $name => $data) {
				if (is_array($data)) {
					if (!$arraySection->isValid())
						continue;

					$frame = $arraySection->createFrame();
					$frame->name = $name;

					$frameSection = $frame->getSection('DATA_SET_FRAME');
					if (count($data)) {
						foreach ($data as $nodeKey => $nodeValue) {
							$nodeFrame = $frameSection->createFrame();
							$nodeFrame->name = $nodeKey;
							$nodeFrame->data = StringUtil::variableAsString($nodeValue);
						}
					} else {
						$nodeFrame = $frameSection->createFrame();
						$nodeFrame->name = '';
						$nodeFrame->data = 'No data to display.';
					}
				} else {
					if (!$stringSection->isValid())
						continue;

					$frame = $stringSection->createFrame();
					$frame->name = $name;
					$frame->data = StringUtil::variableAsString($data);
				}
			}

			return new ErrorReport($this->error, 'text/html; charset=utf-8', '.html', $report);
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

			$template = '';

			// Prepend CSS file.
			if ($this->cssPath !== null) {
				$css = $this->loadTemplateFile($this->cssPath);
				if ($css !== null)
					$template .= '<style type="text/css">' . $css . '</style>';
			}

			// Attempt to load user-provided template.
			if ($this->templatePath !== null) {
				$data = $this->loadTemplateFile($this->templatePath);
				if ($data !== null) {
					$template .= $data;
					return $template;
				}
			}

			// Fallback to KW7 built-in template if possible.
			$template .= $this->loadTemplateFile(__DIR__ . '/../../../templates/error_report.html') ?? '';
			return $template;
		}

		/**
		 * Attempt to load template data from a file.
		 *
		 * @internal
		 * @param string $file File to load the template from.
		 * @return string|null
		 */
		private function loadTemplateFile(string $file) {
			if (file_exists($file) && is_file($file)) {
				$data = file_get_contents($file);
				if ($data !== false) {
					return $data;
				}
			}
			return null;
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
		 * @var array
		 */
		protected $basicData;

		/**
		 * @var array
		 */
		protected $trace;

		/**
		 * @var string|null
		 */
		protected $template;

		/**
		 * @var string|null
		 */
		protected $templatePath;

		/**
		 * @var string|null
		 */
		protected $cssPath;
	}