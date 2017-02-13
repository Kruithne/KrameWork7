<?php

	require_once('resources/ReportingTest/TestReportRunner.php');
	require_once('resources/Cache/DummyCache.php');

	class ReportRunnerTest extends \PHPUnit\Framework\TestCase
	{
		public function testRun() {
			$runner = new TestReportRunner(new DummyCache(), 'test', 0, ['value' => 1]);
			$runner->data();
			$this->assertTrue($runner->runCalled, "Calling data() did not trigger run()");
		}

		public function testPostProcess() {
			$runner = new TestReportRunner(new DummyCache(), 'test', 0, ['value' => 1]);
			$runner->data();
			$this->assertTrue($runner->postProcessCalled, "Calling data() did not run data through postProcess()");
		}

		public function testReportReturnsData() {
			$data = ['value' => 1];
			$runner = new TestReportRunner(new DummyCache(), 'test', 0, $data);
			$this->assertEquals($data, $runner->data()->data, 'Calling data() did not return the expected data');
		}

		public function testReportClear() {
			$data1 = ['value' => 1];
			$data2 = ['value' => 2];
			$runner = new TestReportRunner(new DummyCache(), 'test', 0, $data1);
			$runner->data();
			$runner->clear();
			$runner->data = $data2;
			$this->assertEquals($data2, $runner->data()->data, 'Calling data() did not return the expected data after clear()');
		}

		public function testReportCache() {
			$data = ['value' => 1];
			$runner = new TestReportRunner(new DummyCache(), 'test', 300, $data);
			$runner->data();
			$runner->data = null;
			$this->assertEquals($data, $runner->data()->data, 'Calling data() did not return cached data');
		}

		public function testReportCacheTTL() {
			$data = ['value' => 1];
			$cache = new DummyCache();
			$runner = new TestReportRunner($cache, 'test', 300, $data);
			$runner->data();
			$this->assertEquals(300, $cache->ttl['test'], 'Calling data() did not cache the data for the expected time');
		}
	}