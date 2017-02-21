## KrameWork\Runtime\ErrorFormatters\HTMLErrorFormatter

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `HTMLErrorFormatter` is an implementation of `IErrorFormatter` for use with the `ErrorHandler` class. Error reports will be formatted using a provided HTML template. If no template file is provided, the formatter will attempt to use the default included with KrameWork7, located at `/templates/error_report.php`.
___
### Functions
##### > __construct() : `void`
HTMLErrorFormatter constructor.

parameter | type | description
--- | --- | ---
`$templatePath` | `string|null` | Path to a HTML template to use.
`$cssPath` | `string|null` | Path to a CSS file to prepend.

exception | reason
--- | ---
`InvalidTemplateFileException` | Template file could not be resolved/is invalid.

##### > beginReport() : `void`
Called just before this report is used.

##### > handleError() : `void`
Format an error and add it to the report.

parameter | type | description
--- | --- | ---
`$error` | `IError` | Error which occurred.

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

