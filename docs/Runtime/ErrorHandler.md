## KrameWork\Runtime\ErrorHandler

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `ErrorHandler` is a meaty class that intercepts all exceptions and errors that occur during runtime and produces a report for each, and dispatches said report. The method of report generation and the method of dispatch are controlled by the instances you provide to the error handler, either using ready-made ones provided by KW7, or your own home-cooked ones.

Upon creation, the error handler will take over the current **error handler**, **exception handler** and set the **error level** to `E_ALL`. Disabling the error handler using `deactivate()` will restore these three things back to **before** the error handler was constructed.

In the default configuration, the error handler will terminate script execution after `10` errors have occurred. This value can be changed using the `setMaxErrors()` function, however it is important to note that some dispatchers will halt the script after a single error is dispatched regardless of this value; check the 'Dispatchers' list below to see which ones do.
___
##### Creating an error handler
Creating and setting up an error handler is very simple, simply craft an instance of it and pass in both a report formatter and a dispatcher.
```php
new ErrorHandler(new PlainTextErrorFormatter(), new BufferDispatcher());
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
$errHandler = new ErrorHandler($errFormatter, $errDispatcher);

ob_start([$errHandler, 'catchCoreError']); // Allows ErrorHandler to catch core errors.
```
With the example above, if a core/internal error occurs, the error will be dispatched (in this instance to a file named `error.log`) and the script will be terminated. It is highly recommended that you configure your web server to dispatch an error document when server-response `500` is sent.
___
### Functions
##### > __construct() : `void`
ErrorHandler constructor.

parameter | type | description
--- | --- | ---
`$report` | `IErrorFormatter` | Report class used to format error reports.
`$dispatch` | `IErrorDispatcher` | Dispatch used to output errors.

##### > setMaxErrors(): `void`
Set the maximum amount of errors that can occur before the error handler will terminate the script.

parameter | type | description
--- | --- | ---
`$max` | `int` | Maximum error threshold.

##### > deactivate() : `void`
Disable this error handler, restoring handlers/levels to their state when this error handler was created.