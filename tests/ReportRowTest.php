<?php
	use KrameWork\Data\DateValue;

	require_once(__DIR__.'/../src/Reporting/ReportRow.php');
	require_once(__DIR__.'/../src/Data/DateValue.php');

	class ReportRowTest extends \PHPUnit\Framework\TestCase
	{
		public function testSimpleJsonSerialization() {
			$data = ['test' => 1];
			$row = new \KrameWork\Reporting\ReportRow((object)$data);
			$this->assertEquals($data, $row->jsonSerialize());
		}

		public function testDateValueJsonSerialization() {
			$data = (object)['test' => new DateValue('1980-01-01')];
			$expect = ['test'=> '1980-01-01T00:00:00+00:00'];
			$row = new \KrameWork\Reporting\ReportRow($data);
			$this->assertEquals($expect, $row->jsonSerialize());
		}

		public function testForeachIteration() {
			$data = ['a'=>'test a','b'=>'test b'];
			$expect = join('', $data);
			$row = new \KrameWork\Reporting\ReportRow((object)$data);
			$actual = '';
			foreach ($row as $item)
				$actual .= $item;
			$this->assertEquals($expect, $actual);
		}

		public function testValueRead() {
			$data = (object)['test' => 42];
			$expect = 42;
			$row = new \KrameWork\Reporting\ReportRow($data);
			$actual = $row['test'];
			$this->assertEquals($expect, $actual);
		}

			public function testValueWrite() {
			$data = (object)['test' => 42];
			$expect = 0;
			$row = new \KrameWork\Reporting\ReportRow($data);
			$row['test'] = 0;
			$actual = $row['test'];
			$this->assertEquals($expect, $actual);
		}
	}