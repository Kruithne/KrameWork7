<?php

	use KrameWork\Data\CurrencyValue;
	use KrameWork\Data\DateTimeValue;
	use KrameWork\Data\DateValue;
	use KrameWork\Data\DecimalValue;
	use KrameWork\Data\IntegerValue;
	use KrameWork\Data\StringValue;
	use KrameWork\Data\Value;
	use KrameWork\Reporting\ReportColumn;
	use KrameWork\Reporting\ReportRow;
	use resources\ReportingTest\TestReport;

	require_once(__DIR__.'/resources/ReportingTest/TestReport.php');
	require_once(__DIR__.'/../src/Reporting/ReportColumn.php');
	require_once(__DIR__.'/../src/Reporting/ReportRow.php');
	require_once(__DIR__.'/../src/Data/IntegerValue.php');
	require_once(__DIR__.'/../src/Data/CurrencyValue.php');

	class ReportTest extends \PHPUnit\Framework\TestCase
	{
		public function testValueWrapping() {
			$report = new TestReport(new DummyCache(), 'test', 0);
			$report->data = [[
				'money' => 4.20,
				'time' => '1980-01-01 12:38',
				'date' => '1980-01-01',
				'float' => 8.1,
				'int' => 1,
				'string' => 'Nor shall you here be lost',
				'none' => null,
				'unknown' => 'Go through the open door.'
			]];
			$report->columns = [
				'money' => new ReportColumn('test', ReportColumn::COL_CURRENCY),
				'time' => new ReportColumn('test', ReportColumn::COL_DATETIME),
				'date' => new ReportColumn('test', ReportColumn::COL_DATE),
				'float' => new ReportColumn('test', ReportColumn::COL_DECIMAL),
				'int' => new ReportColumn('test', ReportColumn::COL_INTEGER),
				'string' => new ReportColumn('test', ReportColumn::COL_STRING),
				'none' => new ReportColumn('test', ReportColumn::COL_NONE)
			];
			$actual = $report->data()->data;
			$expected = [
				new ReportRow([
					'money' => new CurrencyValue(4.2),
					'time' => new DateTimeValue('1980-01-01 12:38'),
					'date' => new DateValue('1980-01-01'),
					'float' => new DecimalValue(8.1),
					'int' => new IntegerValue(1),
					'string' => new StringValue('Nor shall you here be lost'),
					'none' => null,
					'unknown' => 'Go through the open door.'
				])
			];
			$this->assertEquals($expected, $actual);
		}
	}