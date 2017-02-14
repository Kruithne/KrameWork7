## KrameWork\Reporting\ReportRunner

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `ReportRunner` class is designed to manage the generation and caching of reports from various sources. This will typically be from a database, but could also be some other complex logic.
___
### Examples
To create a report, you need to make a class extending `ReportRunner`. At a bare minimum, you must override the `run()` method to provide your report data, and the `columns()` method to provide column information.
```php
class MyReport extends ReportRunner
{
  protected function run() {
    return [[1,2,3],[4,5,6]];
  }
  
  public function columns() {
    return [
      new ReportColumn('One'),
      new ReportColumn('Two'),
      new ReportColumn('Three')
    ];
  }
}
```

___
### Functions
##### > __construct() : `void`
ReportRunner constructor.

parameter | type | description
--- | --- | ---
`$cache` | `IDataCache` | The provider handling the caching of report results.
`$key` | `string` | Cache access key for this report.
`$cacheTTL` | `int` | Number of seconds to keep the report results in the cache.

##### > data() : `ReportResults`
Returns the result set of the report, running the report if necessary, or reading the contents from cache.
##### > clear() : `void`
Forces the report to rerun by clearing the cache. A subsequent call to `data()` will execute the report.
##### > columns() : `ReportColumn[]`
Returns the column specifications for the report 