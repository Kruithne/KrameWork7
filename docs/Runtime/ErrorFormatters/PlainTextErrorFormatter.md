## KrameWork\Runtime\ErrorFormatters\PlainTextErrorFormatter

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `PlainTextErrorFormatter` is an implementation of `IErrorFormatter` for use with the `ErrorHandler` class. Error reports will be formatted in plain-text, with `\n` line-endings by default. Below is an example of a report this formatter would produce when encountering a runtime error.

> **Note**: The below example is just that, an example. Some data has been trimmed for the purpose of displaying it here, your milage **will** vary, and actual reports may be juicer with data.

```
RUNTIME ERROR : USER NOTICE
	> Message: Something blew up!
	> Occurred: Fri, 17 Feb 2017 02:17:07 +0000 (1487297827)
	> Script: D:\KrameWork7\src\Runtime\handling.test.php (Line 20)

	> Stack trace [7 steps]:
		PlainTextErrorFormatter.php:104 - KrameWork\Runtime\ErrorTypes\RuntimeError->getTrace()
		ErrorHandler.php:78 - PlainTextErrorFormatter->reportError((RuntimeError) RuntimeError instance)
		interpreter:? - ErrorHandler->catchRuntimeError((integer) 1024, (string) (18) "Something blew up!", (string) (43) "handling.test.php", (integer) 20, (array) 0 items)
		handling.test.php:20 - user_error((string) (18) "Something blew up!", (integer) 1024)
		handling.test.php:16 - TestClass->derp()
		handling.test.php:26 - TestClass->__construct((string) (4) "fuck", (integer) 1)
		handling.test.php:29 - merp((integer) 42)

	> $_SERVER [71 items]
		ALLUSERSPROFILE => (string) (14) "C:\ProgramData"
		APPDATA => (string) (32) "C:\Users\Cthulu\AppData\Roaming"
		CommonProgramFiles => (string) (35) "C:\Program Files (x86)\Common Files"
		CommonProgramFiles(x86) => (string) (35) "C:\Program Files (x86)\Common Files"
		CommonProgramW6432 => (string) (29) "C:\Program Files\Common Files"
		COMPUTERNAME => (string) (9) "CTHULU-PC"
		ComSpec => (string) (27) "C:\Windows\system32\cmd.exe"
		FP_NO_HOST_CHECK => (string) (2) "NO"
		GIT_LFS_PATH => (string) (24) "C:\Program Files\Git LFS"
		HOMEDRIVE => (string) (2) "C:"
		HOMEPATH => (string) (14) "\Users\Cthulu"
		LOCALAPPDATA => (string) (30) "C:\Users\Cthulu\AppData\Local"
		LOGONSERVER => (string) (11) "\\CTHULU-PC"
		MYSQLCONNECTOR_ASSEMBLIESPATH => (string) (62) "C:\MySQL\Connector.NET 6.9\Assemblies\v4.5"
		NUMBER_OF_PROCESSORS => (string) (1) "8"
		OS => (string) (10) "Windows_NT"
		Path => (string) (993) "C:\Ruby23-x64\bin;C:\Program Files (x86)\PHP7"
		PATHEXT => (string) (57) ".COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH;.MSC;.PY"
		PROCESSOR_ARCHITECTURE => (string) (3) "x86"
		PROCESSOR_ARCHITEW6432 => (string) (5) "AMD64"
		PROCESSOR_IDENTIFIER => (string) (50) "Intel64 Family 6 Model 58 Stepping 9, GenuineIntel"
		PROCESSOR_LEVEL => (string) (1) "6"
		PROCESSOR_REVISION => (string) (4) "3a09"
		ProgramData => (string) (14) "C:\ProgramData"
		ProgramFiles => (string) (22) "C:\Program Files (x86)"
		ProgramFiles(x86) => (string) (22) "C:\Program Files (x86)"
		ProgramW6432 => (string) (16) "C:\Program Files"
		PSModulePath => (string) (51) "C:\Windows\system32\WindowsPowerShell\v1.0\Modules\"
		PUBLIC => (string) (15) "C:\Users\Public"
		SESSIONNAME => (string) (7) "Console"
		SystemDrive => (string) (2) "C:"
		SystemRoot => (string) (10) "C:\Windows"
		TEMP => (string) (27) "C:\Users\Cthulu\Local\Temp"
		TMP => (string) (27) "C:\Users\Cthulu\Local\Temp"
		USERDOMAIN => (string) (9) "CTHULU-PC"
		USERNAME => (string) (7) "Cthulu"
		USERPROFILE => (string) (16) "C:\Users\Cthulu"
		VBOX_MSI_INSTALL_PATH => (string) (35) "C:\Program Files\Oracle\VirtualBox\"
		VS110COMNTOOLS => (string) (66) "C:\Program Files (x86)\Microsoft Visual Studio 11.0\Common7\Tools\"
		VS140COMNTOOLS => (string) (66) "C:\Program Files (x86)\Microsoft Visual Studio 14.0\Common7\Tools\"
		windir => (string) (10) "C:\Windows"
		SCRIPT_NAME => (string) (41) "/KrameWork7/src/Runtime/handling.test.php"
		SCRIPT_FILENAME => (string) (43) "D:\KrameWork7\src\Runtime\handling.test.php"
		DOCUMENT_ROOT => (string) (13) "D:\KrameWork7"
		HTTP_CONTENT_LENGTH => (string) (1) "0"
		HTTP_COOKIE => (string) (66) "lang=en-US"
		HTTP_ACCEPT_LANGUAGE => (string) (14) "en-US,en;q=0.8"
		HTTP_ACCEPT_ENCODING => (string) (23) "gzip, deflate, sdch, br"
		HTTP_ACCEPT => (string) (74) "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"
		HTTP_USER_AGENT => (string) (108) "Chrome/55.0.2883.87"
		HTTP_UPGRADE_INSECURE_REQUESTS => (string) (1) "1"
		HTTP_CACHE_CONTROL => (string) (9) "max-age=0"
		HTTP_CONNECTION => (string) (10) "keep-alive"
		HTTP_HOST => (string) (15) "localhost:63342"
		CONTENT_LENGTH => (string) (1) "0"
		QUERY_STRING => (string) (30) "sid=k10ac6g9vskqfg9dj0k7ne67o"
		REDIRECT_STATUS => (string) (3) "200"
		SERVER_PROTOCOL => (string) (8) "HTTP/1.1"
		GATEWAY_INTERFACE => (string) (7) "CGI/1.1"
		SERVER_PORT => (string) (5) "55565"
		SERVER_ADDR => (string) (9) "127.0.0.1"
		SERVER_NAME => (string) (17) "KW7 TestBench 7.0.1 NTS x86"
		SERVER_SOFTWARE => (string) (17) "KW7 TestBench 7.0.1 NTS x86"
		REMOTE_PORT => (string) (5) "62206"
		REMOTE_ADDR => (string) (9) "127.0.0.1"
		REQUEST_METHOD => (string) (3) "GET"
		REQUEST_URI => (string) (72) "/KrameWork7/src/Runtime/handling.test.php?sid=k10ac6g9vskqfg9dj0k7ne67o"
		FCGI_ROLE => (string) (9) "RESPONDER"
		PHP_SELF => (string) (41) "/KrameWork7/src/Runtime/handling.test.php"
		REQUEST_TIME_FLOAT => (double) 1487297827.1276
		REQUEST_TIME => (integer) 1487297827

	> $GLOBALS [7 items]
		_GET => (array) 1 items
		_POST => (array) 0 items
		_COOKIE => (array) 2 items
		_FILES => (array) 0 items
		handler => (KrameWork\Runtime\ErrorHandler) KrameWork\Runtime\ErrorHandler instance
		_SERVER => (array) 71 items
		GLOBALS => (array) 7 items

	> $_POST [empty]
		No data to display

	> $_GET [1 items]
		sid => (string) (25) "k10ac6g9vskqfg9dj0k7ne67o"

	> $_COOKIE [2 items]
		lang => (string) (5) "en-US"

	> $_FILES [empty]
		No data to display

	> Raw Request Content => (string) (0) ""


Report generated automatically on Fri, 17 Feb 2017 02:17:07 +0000 (1487297827).
```
___
### Functions
##### > __construct() : `void`
PlainTextErrorFormatter constructor.

parameter | type | description
--- | --- | ---
`$lineEnd` | `string` | Line-end to use, defaults to `\n`
`$wrapPreTags` | `bool` | Wrap the report in HTML `<pre/>` tags.

##### > beginReport() : `void`
Called just before this report is used.

##### > reportString() : `void`
Format a data string and add it to the report.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the data string.
`$str` | `string` | Data string.

##### > formatArray() : `void`
Format an array and add it to the report.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name for the array.
`$arr` | `array` | Array of data.

##### > handleError() : `void`
Format a report for a runtime error.

parameter | type | description
--- | --- | ---
`$error` | `IError` | Error which occurred.

##### > __toString() : `string`
Compile the report into a string.

