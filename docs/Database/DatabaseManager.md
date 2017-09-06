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
require('KrameWork7/src/Database/Database.php');
require('KrameWork7/src/Database/Schema/MSSQLMetaTable.php');
require('KrameWork7/src/Database/Schema/DatabaseManager.php');
require('KrameWork7/src/Database/Schema/MSSQLManagedTable.php');

use KrameWork\Database\ConnectionString;
use KrameWork\Database\Database;
use KrameWork\Database\Schema\MSSQLManagedTable;
use KrameWork\Database\Schema\MSSQLMetaTable;
use KrameWork\Database\Schema\DatabaseManager;

$username = 'domain\user';
$password = 'password';
$dsn = new ConnectionString('dblib:version=7.0;charset=UTF-8;host=127.0.0.1;dbname=example', $username, $password);
$db = new Database($dsn, Database::DB_DRIVER_PDO);
$dbm = new DatabaseManager();
$meta = new MSSQLMetaTable($db);
$dbm->MyTable = new MyTable($db, $meta);
$status = $dbm->updateSchema();
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

