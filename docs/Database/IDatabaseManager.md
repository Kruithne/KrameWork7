## KrameWork\Database\IDatabaseManager

***Table of Contents***
* **Overview** - Information about the interface.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
This interface defines the necessary functions to implement a schema manager
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

