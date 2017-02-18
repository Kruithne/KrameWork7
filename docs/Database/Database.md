## KrameWork\Database\Database

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
A database connection
___
### Examples
```php
$dsn = new ConnectionString('dblib:version=7.0;charset=UTF-8;host=mssqlsrvr;dbname=database');
$db = new Database($dsn);
//...
$data = $db->getAll('SELECT * FROM users WHERE id = :id', ['id' => $id]);
//...
$data = $db->getAll('SELECT * FROM acl WHERE userid = ?', [$data->id]);
```

___
### Functions
##### > __construct() : `void`
Database constructor.

parameter | type | description
--- | --- | ---
`$connection` | `ConnectionString` | Connection string specifying how to connect
`$driver` | `int` | One of the Database::DB_DRIVER_ constants

exception | reason
--- | ---
`UnknownDriverException` | The specified driver is unknown to the system

##### > getAll() : `\ArrayObject[]`
Execute a query and return an array of ArrayObjects

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

##### > getColumn() : `array`
Execute a query and return the first column of each row

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

