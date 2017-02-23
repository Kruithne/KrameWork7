## KrameWork\Runtime\ErrorDispatchers\MailDispatcher

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `MailDispatcher` class is an implementation of `IErrorDispatcher` for use with the `ErrorHandler` class. If using this class as the error dispatcher, reports will be mailed to the given recipients. The name of the report (used as the subject) can be controlled with the second parameter as either a string or a callable generator.
___
### Example
This is a use-case example for this dispatcher, using a naming callback instead of a static string.
```php
$generator = function($report) {
	// $report is of type string|IErrorReport, depending on what is being dispatched.
    return 'Error Report: ' . md5(time() + mt_rand()); 
};

$dispatcher = new MailDispatcher(
    ['foo@bar.net' => 'Foo Bar'], // Recipient array.
    'error-reporting@bar.net', // Sender address.
    'Error Reporter', // Sender name.
    $generator // Report/subject name generator/string.
);
// Provide $dispatcher to an instance of ErrorHandler.
```
___
### Functions
##### > __construct() : `void`
MailDispatcher constructor.

parameter | type | description
--- | --- |---
`$recipients` | `array` | Recipient array, formatted in accordance to the `Mail` class.
`$sender` | `string` | E-mail address the error reports are sent from.
`$senderName` | `string` | Name of the e-mail sender, defaults to `$sender`.
`$subject` | `string|array|callable` | Subject/report name string or generator.

##### > dispatch() : `bool`
Dispatch an error report.

parameter | type | description
--- | --- | ---
`$report` | `string|IErrorReport` | Report to dispatch.