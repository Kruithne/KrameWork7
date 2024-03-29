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
//...
$db->execute('INSERT INTO `animals` (`name`) VALUES(:name)', ['name' => 'monkey']);
$animalID = $db->getLastInsertID();
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

##### > getLastError() : `mixed`
Retrieve the last error that occurred on this database connection. Return value is subject to the underlying driver.

For PDO, see [PDO::errorInfo](https://www.php.net/manual/en/pdo.errorinfo.php). For usage with prepared statements, use `getLastQueryError()`, as `getLastError()` will only return errors on the connection handle.

##### > getLastQueryError() : `mixed`
Retrieve the last query error that occurred. Return value is subject to the underlying driver.

For PDO, see [PDOStatement::errorInfo](https://www.php.net/manual/en/pdostatement.errorinfo.php). This only retrieves the error for the last executed prepared statement.

##### > getLastInsertID() : `string`
Returns the ID of the last inserted row.

##### > beginTransaction : `void`
Start a database transaction

##### > commitTransaction : `void`
Commit a database transaction

##### > rollbackTransaction : `void`
Roll back a database transaction