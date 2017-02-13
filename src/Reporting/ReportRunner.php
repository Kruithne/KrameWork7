<?php
	/*
	 * Copyright (c) 2017 Morten Nilsen (morten@runsafe.no)
	 * https://github.com/Kruithne/KrameWork7
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

	namespace KrameWork\Reporting;

	use KrameWork\Caching\IDataCache;

	/**
	 * Class SQLReportRunner
	 * Encapsulates executing and caching data from SQL
	 */
	abstract class ReportRunner
	{
		/**
		 * ReportRunner constructor.
		 * @param IDataCache $cache The cache location for the report data
		 * @param string $key The key to access the cached report data
		 * @param int $cacheTTL The number of seconds the report should remain cached
		 */
		public function __construct(IDataCache $cache, string $key, int $cacheTTL = 300) {
			$this->cacheTTL = $cacheTTL;
			$this->key = $key;
			$this->cache = $cache;
		}

		/**
		 * Executes the report, storing the results in cache.
		 * Override this to implement your report.
		 */
		protected abstract function run();

		/**
		 * Executes the report if necessary and returns the cached result set.
		 * @return ReportResults The data set returned by the SQL
		 */
		public function data() {
			if (!$this->cache->exists($this->key))
				$this->cache->store($this->key, new ReportResults($this->run()), time() + $this->cacheTTL);
			return $this->cache->__get($this->key);
		}

		/**
		 * Flushes cached data, forcing a fresh run of the report
		 */
		public function clear() {
			$this->cache->__unset($this->key);
		}

		/**
		 * Override this method to do post-processing of the data returned by the SQL
		 * @param array $data
		 * @return array
		 */
		protected function postProcess(array $data) {
			return $data;
		}

		/**
		 * @var int Cache Time To Live
		 */
		protected $cacheTTL;

		/**
		 * @var string Cache storage key
		 */
		protected $key;

		/**
		 * @var IDataCache Cache engine
		 */
		protected $cache;
	}
