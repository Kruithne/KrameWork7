<?php

	use KrameWork\Data\CurrencyValue;
	use KrameWork\Data\DateTimeValue;
	use KrameWork\Data\DateValue;
	use KrameWork\Data\DecimalValue;
	use KrameWork\Data\IntegerValue;
	use KrameWork\Data\StringValue;
	use KrameWork\Reporting\ReportColumn;
	use KrameWork\Reporting\ReportRow;

	require_once('resources/ReportingTest/TestReportRunner.php');
	require_once('resources/Cache/DummyCache.php');

	class ReportRunnerTest extends \PHPUnit\Framework\TestCase
	{
		public function testRun() {
			$runner = new TestReportRunner(new DummyCache(), 'test', 0);
			$runner->data = [['value' => 1]];
			$runner->data();
			$this->assertTrue($runner->runCalled, "Calling data() did not trigger run()");
		}

		public function testPostProcess() {
			$runner = new TestReportRunner(new DummyCache(), 'test', 0);
			$runner->data = [['value' => 1]];
			$runner->data();
			$this->assertTrue($runner->postProcessCalled, "Calling data() did not run data through postProcess()");
		}

		public function testReportReturnsData() {
			$data = ['value' => 1];
			$runner = new TestReportRunner(new DummyCache(), 'test', 0);
			$runner->data = [$data];
			$this->assertEquals([new ReportRow($data)], $runner->data()->data, 'Calling data() did not return the expected data');
		}

		public function testReportClear() {
			$data1 = ['value' => 1];
			$data2 = ['value' => 2];
			$runner = new TestReportRunner(new DummyCache(), 'test', 0);
			$runner->data = [$data1];
			$runner->data();
			$runner->clear();
			$runner->data = [$data2];
			$this->assertEquals([new ReportRow($data2)], $runner->data()->data, 'Calling data() did not return the expected data after clear()');
		}

		public function testReportCache() {
			$data = ['value' => 1];
			$runner = new TestReportRunner(new DummyCache(), 'test', 300);
			$runner->data = [$data];
			$runner->data();
			$runner->data = null;
			$this->assertEquals([new ReportRow($data)], $runner->data()->data, 'Calling data() did not return cached data');
		}

		public function testReportCacheTTL() {
			$data = ['value' => 1];
			$cache = new DummyCache();
			$runner = new TestReportRunner($cache, 'test', 300);
			$runner->data = [$data];
			$runner->data();
			$this->assertEquals(300, $cache->ttl['test'], 'Calling data() did not cache the data for the expected time');
		}

		public function testValueWrapping() {
			$report = new TestReportRunner(new DummyCache(), 'test', 0);
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