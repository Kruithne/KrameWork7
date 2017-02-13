<?php
	use KrameWork\Data\DateValue;

	require_once(__DIR__.'/../src/Reporting/ReportRow.php');
	require_once(__DIR__.'/../src/Data/DateValue.php');

	class ReportRowTest extends \PHPUnit\Framework\TestCase
	{
		public function testSimpleJsonSerialization() {
			$data = [['test' => 1],['test' => 2]];
			$row = new \KrameWork\Reporting\ReportRow($data);
			$this->assertEquals($data, $row->jsonSerialize());
		}

		public function testDateValueJsonSerialization() {
			$data = [['test' => 1],['test' => new DateValue('1980-01-01')]];
			$expect = [['test' => 1],['test'=> '1980-01-01T00:00:00+00:00']];
			$row = new \KrameWork\Reporting\ReportRow($data);
			$this->assertEquals($expect, $row->jsonSerialize());
		}
	}