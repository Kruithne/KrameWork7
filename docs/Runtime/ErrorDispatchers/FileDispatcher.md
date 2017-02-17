## KrameWork\Runtime\ErrorDispatchers\FileDispatcher

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `FileDispatcher` class is an implementation of `IErrorDispatcher` for use with the `ErrorHandler` class. If using this class as the error dispatcher, reports will be written to a file, the name of which can be controlled by the second parameter of the constructor.

Each report will be created in a new file, which means if the file already exists, the dispatcher will append a value onto the end to keep it unique. For example, if you use the default report name, `error`, then your reports will be `error`, `error_1`, `error_2` and so on, which the extension specific to the formatter.

> **Note**: If the directory provided to this error report cannot be resolved, the dispatcher will instead revert to using `__DIR__` rather than throwing any kind of error. Ensure you use a valid path which the dispatcher can write to.

___
### Example
This is a use-case example for this dispatcher, using a naming callback instead of a static string.
```php
function createLogName():string {
    return md5(time() + mt_rand());
}

$dispatcher = new FileDispatcher(__DIR__, ['createLogName']);
// Provide $dispatcher to an instance of ErrorHandler.
```
___
### Functions
##### > __construct() : `void`
FileDispatcher constructor.

parameter | type | description
--- | --- |---
`$directory` | `string` | Directory which report files will be stored in.
`$name` | `string|array` | File-name or callable naming function.

##### > dispatch() : `void`
Dispatch an error report.

parameter | type | description
--- | --- | ---
`$report` | `string` | Report to dispatch.