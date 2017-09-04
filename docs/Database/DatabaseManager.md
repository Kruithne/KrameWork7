## KrameWork\Database\DatabaseManager

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
This interface defines the necessary functions to implement a schema manager

___
### Examples
Define one or more classes extending ManagedTable and add them to an instance of DatabaseManager.

```php
$dsn = new ConnectionString('dblib:version=7.0;charset=UTF-8;host=mssqlsrvr;dbname=database');
$db = new Database($dsn);
$meta = new MetaTable($db);
$dbm = new DatabaseManager($db);
$db->MyTable = new MyTable($db, $meta);
$status = $db->updateSchema();
var_dump($status);
```
___
### Functions
##### > __set() : `void`
Add a managed table to the schema manager

parameter | type | description
--- | --- | ---
`$name` | `string` | 
`$table` | `IManagedTable` | 

##### > __get() : `IManagedTable`
Fetch a managed table from the schema manager

parameter | type | description
--- | --- | ---
`$name` | `string` | 

##### > updateSchema() : `array`
Update the database schema according to managed tables

