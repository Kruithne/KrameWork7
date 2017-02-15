<?php

	require_once(__DIR__.'/../src/Reporting/ReportResults.php');

	use KrameWork\Reporting\ReportResults;

	class ReportResultsTest extends \PHPUnit\Framework\TestCase
	{
		public function testResultsHasHash() {
			$results = new ReportResults([]);
			$this->assertAttributeNotEmpty('hash', $results);
		}

		public function testResultsHashUnique() {
			$result1 = new ReportResults([1]);
			$result2 = new ReportResults([2]);
			$this->assertNotEquals($result1->hash, $result2->hash);
		}

		public function testResultsHashReproducible() {
			$result1 = new ReportResults([1]);
			$result2 = new ReportResults([1]);
			$this->assertEquals($result1->hash, $result2->hash);
		}

		public function testResultsAvailable() {
			$result = new ReportResults([1]);
			$expected = [1];
			$this->assertEquals($expected, $result->data);
		}
	}