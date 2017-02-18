## KrameWork\Database

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.
___
### Overview
This class provides timing information for queries.
If a query takes longer than the specified threshold, an `E_USER_ERROR` error will be raised, presumably to be handled by an `ErrorHandler`.
___
### Functions
##### > __construct() : `void`
MonitoredDatabase constructor.

parameter | type | description
--- | --- | ---
`$connection` | `ConnectionString` | A connection string
`$driver` | `int` | A Database::DB_DRIVER_ constant
`$threshold` | `int` | Trigger a warning if execution takes longer than $threshold seconds

##### > getAll() : `\ArrayObject[]`
Execute a query and return an array of ArrayObjects

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement

##### > getColumn() : `array`
Execute a query and return the first column of each row

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement

##### > getRow() : `\ArrayObject`
Execute a query and return the first row as an ArrayObject

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement

##### > getValue() : `mixed`
Execute a query and return the first value of the first row

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL query statement
`$param` | `array` | An array of values to inject in the statement

##### > execute() : `int`
Execute a statement and return the number of affected rows

parameter | type | description
--- | --- | ---
`$sql` | `string` | An SQL statement
`$param` | `array` | An array of values to inject in the statement

##### > getStatistics() : `array`
Returns statistics data collected over the lifetime of the object

