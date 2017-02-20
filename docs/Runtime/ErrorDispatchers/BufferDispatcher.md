## KrameWork\Runtime\ErrorDispatchers\BufferDispatcher

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `BufferDispatcher` class is an implementation of `IErrorDispatcher` for use with the `ErrorHandler` class. If using this class as the error dispatcher, upon being given a report, the PHP output buffer will be cleared and the report will be pushed into the buffer.

> **Note**: Upon dispatching of a single error, this dispatcher will terminate the script.

___
### Functions
##### > dispatch() : `bool`
Dispatch an error report.

parameter | type | description
--- | --- | ---
`$report` | `string|IErrorReport` | Report to dispatch.