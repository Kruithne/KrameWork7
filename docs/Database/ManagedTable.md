## KrameWork\Database\Schema\ManagedTable

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
A base definition of a table that is managed by a schema manager.
Specific dialects are subtypes of this class, such as `MSSQLManagedTable` for Microsoft SQL Server.

___
### Examples
```php
class MyTable extends MSSQLManagedTable
{
    public function getName()
    {
        return "mytable";
    }

    public function versionLog()
    {
        return [
            new TableVersion(
                1,
                ['CREATE TABLE [dbo].[MyTable] ([column] VARCHAR(50) NOT NULL)']
            )
        ];
    }

    public function latestVersion()
    {
        return ['CREATE TABLE [dbo].[MyTable] ([column] VARCHAR(50) NOT NULL)'];
    }
}
```

___
### Functions

##### > getSchema() : `string`
Returns the name of the schema.

##### > getName() : `string`
Returns the name of the table, without schema.

##### > getFullName() : `string`
Returns the fully qualified name of the table, ie. `schema.table`

##### > versionLog() : `TableVersion[]`
Returns an array of TableVersion definition
Used by the schema manager for updating the database.

##### > latestVersion() : `string[]`
Returns a set of queries to create the latest revision of the table.
Used by the schema manager to initialize a clean table.
That is, during initial setup of the schema on a an empty database, or a new table.

##### > update() : `string`
Called by the schema manager to perform an update.
Returns a status message passed on to the caller.

##### > drop() : `void`
Delete the table from the database

##### > create() : `string`
Creates the current version of the table in the database.
Returns a status message passed on to the caller.