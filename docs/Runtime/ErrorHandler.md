## KrameWork\Runtime\ErrorHandler

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `ErrorHandler` is a meaty class that intercepts all exceptions and errors that occur during runtime and produces a report for each, and dispatches said report. The method of report generation and the method of dispatch are controlled by the instances you provide to the error handler, either using ready-made ones provided by KW7, or your own home-cooked ones.

Upon creation, the error handler will take over the current **error handler**, **exception handler** and set the **error level** to `E_ALL`. Disabling the error handler using `deactivate()` will restore these three things back to **before** the error handler was constructed. While the error level is set to `E_ALL`, the handler reserve the level of reporting set before it took over and only dispatch errors of that level. If you wish to change the reporting level after the handler has been created, you can do so with a call to `setErrorLevel()`.

In the default configuration, the error handler will terminate script execution after `10` errors have occurred. This value can be changed using the `setMaxErrors()` function, however it is important to note that some dispatchers will halt the script after a single error is dispatched regardless of this value; check the 'Dispatchers' list below to see which ones do.
___
### Examples

##### Creating an error handler
Creating and setting up an error handler is very simple, simply craft an instance of it and pass in both a report formatter and a dispatcher.
```php
$handler = new ErrorHandler();
$handler->addDispatch(new BufferDispatcher(), new PlainTextErrorFormatter());
```
Using `addDispatch()`, multiple dispatchers and formatters can be paired together, allowing different methods of simultaneous reporting for errors. If you plan to only use one pair, you can provide them with the constructor as follows.
```php
$handler = new ErrorHandler(new BufferDispatcher(), new PlainTextErrorFormatter());
```
The above configuration will clear all output buffers and produce a plain-text report of an error that occurs. Naturally, this is a terrible mess, so spend some time and check out the different formatters and dispatchers KW7 provides, shown below.
##### Formatters
The formatter is responsible for taking the raw data of errors that occur and producing a report with it. Some formatters have unique behavior which can be controlled, check out the individual documentation for each formatter for more information.

| class | description |
| ----- | ----------- |
| `PlainTextErrorFormatter` | Produces a plain-text report with `\n` line-endings (by default). |
| `HTMLErrorFormatter` | HTML formatted report (uses provided template). |
| `JSONErrorFormatter` | Produces a raw JSON formatted dump. |
##### Dispatchers
The dispatcher is responsible for taking the generated report and sending it somewhere. Some dispatchers have unique behavior which can be controlled, check out the individual documentation for each dispatcher for more information.

| class | description | halts script |
| ----- | ----------- | ----------------------- |
| `BufferDispatcher` | Clears the PHP output buffer and dumps the report there. | `true` |
| `FileDispatcher` | Creates a flat-file for each error report. | `false` |

___
##### Catching Core Errors
Without additional configuration, the `ErrorHandler` class cannot catch core PHP errors. To enable this functionality, add the following options to your PHP runtime configuration.
```
error_prepend_string = "<!--[INTERNAL_ERROR]"
error_append_string = "-->"
html_errors = Off
display_errors = On
display_startup_errors = On
auto_prepend_file = /path/to/error.php
```
In addition to these changes to your runtime configuration, you'll also need to provide the output buffer to the `ErrorHandler` class, this should be done within the file referenced as `auto_prepend_file` above; below is a basic example of that file.
```php
// * Specify auto-loader or import needed ErrorHandler classes from KW7 here.
$errFormatter = new PlainTextErrorFormatter();
$errDispatcher = new FileDispatcher();
$errHandler = new ErrorHandler();
$errHandler->addDispatch($errDispatcher, $errFormatter);

ob_start([$errHandler, 'catchCoreError']); // Allows ErrorHandler to catch core errors.
```
With the example above, if a core/internal error occurs, the error will be dispatched (in this instance to a file named `error.log`) and the script will be terminated. It is highly recommended that you configure your web server to dispatch an error document when server-response `500` is sent.

##### Reporting a caught exception
If you are handling exceptions, you might still want to receive reports about them.
To do this, simply call `logException` on your handler.
Be mindful of your max errors as set with `setMaxErrors` and what dispatchers you are using if you do this.
```php
try {
  $something->call();
} catch(SomeException $e) {
  $errHandler->logException($e);
  $something->cleanup();
  return null;
} catch(Exception $e) {
  $errHandler->logException($e);
  return false;
}
```
___
### Functions
##### > __construct() : `void`
ErrorHandler constructor.

parameter | type | description
--- | --- | ---
`$dispatch` | `IErrorDispatcher` | Dispatch used to output errors.
`$report` | `IErrorFormatter` | Report class used to format error reports.

##### > addDispatch() : `void`
Add a dispatcher to this error handler, with a linked formatter.

parameter | type | description
--- | --- | ---
`$dispatcher` | `IErrorDispatcher` | Dispatcher to send reports.
`$formatter` | `IErrorFormatter` | Formatter for dispatcher to use.

##### > setErrorLevel(): `void`
Set the error reporting level for this handler.

parameter | type | description
--- | --- | ---
`$level` | `int` | Error level to set for this handler.

##### > setMaxErrors(): `void`
Set the maximum amount of errors that can occur before the error handler will terminate the script.

parameter | type | description
--- | --- | ---
`$max` | `int` | Maximum error threshold.

##### > addHook(): `void`
Register a debug provider with this error handler, that will be invoked to gather application specific data to add to any error report.

parameter | type | description
--- | --- | ---
`$hook` | `IDebugHook` | An application specific debug provider

##### > deactivate() : `void`
Disable this error handler, restoring handlers/levels to their state when this error handler was created.
##### > logException() : `void`
When you catch an exception, you can feed to this method log it.
You probably do not want to do this if you are using the BufferDispatcher.

parameter | type | description
--- | --- | ---
`$exception` | `Throwable` | An exception to report