## KrameWork\Timing\Benchmarking\BenchmarkReport

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage.
* **Constants** - Constants exposed in the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`BenchmarkReport` class provides a simple reporting interface for `Benchmark` instances. Create benchmarks using the `Benchmark` class, add them a single instance of `BenchmarkReport`, and produce a sorted table of various formats with the results.
___
### Example
```php
$b_dq = new class (1000, 1000000, 'Double Quotes') extends Benchmark {
	public function execute() {
		$str = "Foo" . "Foo" . "Foo";
	}
};

$b_sq = new class (1000, 1000000, 'Single Quotes') extends Benchmark {
	public function execute() {
		$str = 'Foo' . 'Foo'. 'Foo';
	}
};

$b_sprintf = new class (1000, 1000000, 'sprintf') extends Benchmark {
	public function execute() {
		$str = sprintf('%s%s%s', 'Foo', 'Foo', 'Foo');
	}
};

$b_ilv = new class (1000, 1000000, 'Inline Variables') extends Benchmark {
	public function execute() {
		$foo = 'Foo';
		$str = "$foo$foo$foo";
	}
};

$b_arr = new class(1000, 1000000, 'Arr Implosion') extends Benchmark {
	public function execute() {
		$str = implode('', ['Foo', 'Foo', 'Foo']);
	}
};

$report = new BenchmarkReport(BenchmarkReport::FORMAT_MARKDOWN);
$report->add($b_dq, $b_sq, $b_sprintf, $b_ilv, $b_arr);
print($report);
```
___
### Constants
| constant | value | description |
| --- | --- | --- |
| `FORMAT_PLAIN` | `0x1` | Report will be formatted in plain-text. |
| `FORMAT_MARKDOWN` | `0x2` | Report will be formatted in Markdown. |
| `FORMAT_HTML` | `0x4` | Report will be formatted in HTML. |
___
### Functions
##### > __construct() : `void`
BenchmarkReport constructor.

parameter | type | description
--- | --- | ---
`$format` | `int` | Formatter to use, defaults to `FORMAT_HTML`.

##### > add() : `BenchmarkReport`
Add one or more benchmarks to this report.

parameter | type | description
--- | --- | ---
`$benchmarks` | `...Benchmark` | Variable amount of benchmarks to add.

##### > remove() : `BenchmarkReport`
Remove a benchmark from the report.

parameter | type | description
--- | --- | ---
`$benchmark` | `Benchmark` | Benchmark to remove from the report.

##### > clear() : `BenchmarkReport`
Clear all benchmarks from the report.

##### > run() : `string`
Run all tests contained in the report and return a formatted output.

##### > __toString() : `string`
Runs all of the tests and returns the output.

