## KrameWork\Reporting\ReportResults

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `ReportResults` class holds a collection of rows, and stores a hash of the result set.
This is facilitates report consumers detecting a change in the result set, in order to update calculated presentation data as required.

When the object is constructed, a SHA-1 hash is calculated automatically and stored.
___
### Functions
##### > __construct() : `void`
ReportRunner constructor.

parameter | type | description
--- | --- | ---
`$data` | `array|Value[]` | Data values to hold in the row.