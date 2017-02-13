<?php
	namespace KrameWork\Reporting;

	/**
	 * Class SQLReport
	 * Encapsulates a formatted SQL report, adding column definitions with data types
	 */
	abstract class Report extends ReportRunner
	{
		/**
		 * @return ReportColumn[]
		 */
		public abstract function columns();

		/**
		 * @return \Closure[] Filters in the format function(&$row)
		 */
		protected function getFilters() {
			$filters = [];
			foreach ($this->columns() as $key => $col) {
				switch ($col->type) {
					case ReportColumn::COL_DECIMAL:
					case ReportColumn::COL_CURRENCY:
					case ReportColumn::COL_INTEGER:
					case ReportColumn::COL_DATETIME:
					case ReportColumn::COL_DATE:
						$filters[] = self::makeFilter($key, $col->type . 'Value');
						break;
				}
			}
			return $filters;
		}

		/**
		 * @param string $key Column name
		 * @param string $class Column container class
		 * @return \Closure
		 */
		protected function makeFilter(string $key, string $class) {
			return function (&$row) use ($key, $class) {
				if (!isset($row[$key]) || $row[$key] == null)
					return;
				$row[$key] = new $class($row[$key]);
			};
		}

		/**
		 * Format data values for presentation based on column type
		 * @param array $data
		 * @return ReportRow[]
		 */
		protected function postProcess(array $data) {
			$filters = $this->getFilters();
			$output = [];
			foreach ($data as $row) {
				foreach ($filters as $filter)
					$filter($row);
				$output[] = new ReportRow($row);
			}
			return $output;
		}
	}