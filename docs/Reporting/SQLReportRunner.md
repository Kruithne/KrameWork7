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
class MyReport extends SQLReportRunner
{
  public function __construct(IDataCache $cache, Generic $db) {
    $sql = '
SELECT ...
FROM ...
JOIN ...
...
';
    parent::__construct($cache, $db, $sql, [], 300);
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

##### > __construct() : `void`
SQLReport constructor.

parameter | type | description
--- | --- | ---
`$cache` | `IDataCache` | A cache to hold report data
`$db` | `Generic` | A database access object ie. Database
`$sql` | `string` | An SQL Query
`$param` | `array` | Query parameters
`$cacheTTL` | `int` | Number of seconds to store the results in cache.

##### > run() : `void`
Executes the report, storing the results in APC

