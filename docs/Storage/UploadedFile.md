## KrameWork\Storage\UploadedFile

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.
___
### Overview
The `UploadedFile` class is a bare-bones extension of the `File` class which represents a file uploaded alongside a HTTP request. The `HTTPContext` class will provide instances of this class when uploaded files are requested.
___
### Functions
##### > __construct() : `void`
UploadedFile constructor.

parameter | type | description
--- | --- | ---
`$path` | `string` | Temporary location.
`$name` | `string` | Uploaded name.
`$errorCode` | `int` | Upload error code.
##### > isValid() : `bool`
Check if the uploaded file is valid.
##### > getErrorCode() : `int`
Get the error code for this upload.