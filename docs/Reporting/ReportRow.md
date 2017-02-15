## KrameWork\Reporting\ReportRow

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `ReportRow` class serves as a collection of values in a report result. It provides methods to iterate the row, and serialize it to JSON for building APIs.
Normally, rows would be constructed by the `ReportRunner` class and consumed by your report renderer.

This class does not provide any special functions, other than those required by the interfaces `JsonSerializable`, `Iterator`, and `ArrayAccess` to facilitate iteration, serialization, and data access.
___
### Functions
##### > __construct() : `void`
ReportRunner constructor.

parameter | type | description
--- | --- | ---
`$data` | `array|Value[]` | Data values to hold in the row.

