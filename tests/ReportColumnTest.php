<?php

	use KrameWork\Reporting\ReportColumn;

	require_once(__DIR__.'/../src/Reporting/ReportColumn.php');

	class ReportColumnTest extends \PHPUnit\Framework\TestCase
	{
		public function testDefaultColumnType()
		{
			$column = new ReportColumn('');
			$this->assertEquals($column->type, ReportColumn::COL_STRING);
		}

		public function testUnspecifiedColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_NONE);
			$this->assertEquals($column->type, ReportColumn::COL_NONE);
		}

		public function testDateColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_DATE);
			$this->assertEquals($column->type, ReportColumn::COL_DATE);
		}

		public function testDateTimeColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_DATETIME);
			$this->assertEquals($column->type, ReportColumn::COL_DATETIME);
		}

		public function testStringColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_STRING);
			$this->assertEquals($column->type, ReportColumn::COL_STRING);
		}

		public function testCurrencyColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_CURRENCY);
			$this->assertEquals($column->type, ReportColumn::COL_CURRENCY);
		}

		public function testCustomColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_CUSTOM);
			$this->assertEquals($column->type, ReportColumn::COL_CUSTOM);
		}

		public function testIntegerColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_INTEGER);
			$this->assertEquals($column->type, ReportColumn::COL_INTEGER);
		}

		public function testDecimalColumnType()
		{
			$column = new ReportColumn('', ReportColumn::COL_DECIMAL);
			$this->assertEquals($column->type, ReportColumn::COL_DECIMAL);
		}

		public function testInvalidColumnType()
		{
			try {
				new ReportColumn('', 'Invalid');
				$this->fail("Column did not throw exception on invalid type specification.");
			}
			catch(Exception $e)
			{
				$this->assertTrue(true, "World error, restart universe");
				//Expected
			}
		}

		public function testColumnLabel()
		{
			$column = new ReportColumn('test');
			$this->assertEquals('test', $column->label);
		}
	}