## KrameWork\Database\DatabaseCache

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
This class connects a `Database` instance to an `IDataCache` instance, automatically caching the data returned from queries.

___
### Examples
```php
$database = new Database($dsn);
$cache = new APCu();
$cached_db = new DatabaseCache($cache, $database, 300);
$data = $cached_db->getAll('SELECT data FROM expensive_source');
```
___
### Functions
##### > __construct()
Constructor method

parameter | type | description
--- | --- | ---
`$cache` | `IDataCache` | The cache to use for storing results 
`$database` | `Database` | A database connection to run queries through
`$defaultTtl` | `int` | The default time to live value for the cached data

##### > getAll() : `array|\ArrayObject[]`
Execute a query and return an array of ArrayObjects, caching the results.

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement
`$ttl` | `int` | Number of seconds to cache the results, 0 forces refresh

##### > getRow() : `\ArrayObject`
Execute a query and return the first row as an ArrayObject, caching the result.

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement
`$ttl` | `int` | Number of seconds to cache the results, 0 forces refresh

##### > getColumn() : `array`
Execute a query and return the first column of each row, caching the results.

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement
`$ttl` | `int` | Number of seconds to cache the results, 0 forces refresh

##### > getValue() : `mixed`
Execute a query and return the first value of the first row, caching the value.

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement
`$ttl` | `int` | Number of seconds to cache the results, 0 forces refresh

##### > execute() : `int`
Execute a statement and return the number of affected rows, without caching the result.

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL statement
`$param` | `array` | An array of values to inject in the statement

