## KrameWork\Database\Schema\TableVersion

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
A representation of a version of a table
___
### Examples
The most common use case
```php
new TableVersion(1, 'CREATE TABLE [MyTable] ([column] VARCHAR(50) NOT NULL)')
```

Adding custom code to be executed on upgrade
```php
class MyTableV2 extends TableVersion
{
  public function __construct()
  {
    parent::__construct(2, 'ALTER TABLE ADD [text] varchar(50) NULL');
  }
  
  public function BeforeExecution(Generic $db) {
    $db->execute('UPDATE [MyTable] SET foo=bar WHERE foo < 10');
  }
  
  public function AfterExecution(Generic $db) {
    $db->execute('UPDATE [MyTable] SET [text] = foo WHERE foo > 2');
  }
}
```

___
### Functions
##### > __construct() : `void`
Table version constructor.

parameter | type | description
--- | --- | ---
`$version` | `int` | The numerical version
`$sql` | `string[]` | A set of SQL statements to execute when applying this version.

##### > BeforeExecution() : `void`
Method called just before applying the update.
Override this if you need to perform some logic before the application of the version.

##### > AfterExecution() : `void`
Method called just after applying the update.
Override this if you need to perform some logic after the application of the version.
