## KrameWork\Runtime\ErrorFormatters\JSONErrorFormatter

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `JSONErrorFormatter` is an implementation of `IErrorFormatter` for use with the `ErrorHandler` class. Error reports will be dumped in an encoded JSON object.
___
### Functions
##### > beginReport() : `void`
Called just before this report is used.

##### > handleError() : `void`
Format an error and add it to the report.

parameter | type | description
--- | --- | ---
`$error` | `IError` | Error which occurred.

##### > reportDebug() : `void`
Format application debug data and add it to the report.

parameter | type | description
--- | --- | ---
`$debug` | array | Array of key/value pairs

##### > formatArray() : `void`
Format an array and add it to the report.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name for the array.
`$arr` | `array` | Array of data.

##### > reportString() : `void`
Format a data string and add it to the report.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the data string.
`$str` | `string` | Data string.

##### > generate() : `IErrorReport`
Generate a report.

