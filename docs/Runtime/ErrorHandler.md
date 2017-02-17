## KrameWork\Runtime\ErrorHandler

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `ErrorHandler` is a meaty class that intercepts all exceptions and errors that occur during runtime and produces a report for each, and dispatches said report. The method of report generation and the method of dispatch are controlled by the instances you provide to the error handler, either using ready-made ones provided by KW7, or your own home-cooked ones.

Upon creation, the error handler will take over the current **error handler**, **exception handler** and set the **error level** to `E_ALL`. Disabling the error handler using `deactivate()` will restore these three things back to **before** the error handler was constructed.
___
##### Creating an error handler
Creating and setting up an error handler is very simple, simply craft an instance of it and pass in both a report formatter and a dispatcher.
```php
new ErrorHandler(new PlainTextErrorFormatter(), new BufferDispatch());
```
The above configuration will clear all output buffers and produce a plain-text report of an error that occurs. Naturally, this is a terrible mess, so spend some time and check out the different formatters and dispatchers KW7 provides, shown below.
##### Formatters
The formatter is responsible for taking the raw data of errors that occur and producing a report with it. Some formatters have unique behavior which can be controlled, check out the individual documentation for each formatter for more information.

| class | description |
| ----- | ----------- |
| `PlainTextErrorFormatter` | Produces a plain-text report with `\n` line-endings (by default). |
##### Dispatchers
The dispatcher is responsible for taking the generated report and sending it somewhere. Some dispatchers have unique behavior which can be controlled, check out the individual documentation for each dispatcher for more information.
| class | description | halts script |
| ----- | ----------- | ----------------------- |
| `BufferDispatch` | Clears the PHP output buffer and dumps the report there. | `true` |
___
### Functions
##### > __construct() : `void`
ErrorHandler constructor.

parameter | type | description
--- | --- | ---
`$report` | `IErrorFormatter` | Report class used to format error reports.
`$dispatch` | `IErrorDispatcher` | Dispatch used to output errors.

##### > deactivate() : `void`
Disable this error handler, restoring handlers/levels to their state when this error handler was created.