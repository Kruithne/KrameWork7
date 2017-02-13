## KrameWork\HTTPContext

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Constants** - Constants exposed by this class.
* **Functions** - Comprehensive list of all functions in the class.
___
### Examples
##### JSON Requests
 If you're expecting the request as JSON, the `HTTPContext` class can automatically validate and parse it for you, providing you with a JSON object.
```php
$http = new HTTPContext();
$json = $http->getJSON(); // Hooray, JSON!
```
By default, the request data will be decoded, and the content-type header will be checked for `application/json`. If either of these fail, an `InvalidRequestTypeException` will be thrown. The behavior can be controlled by the two parameters for the call: `getJSON(bool $decode = true, bool $ignoreContentType = false);`.

Omitting both these checks will render the function redundant, and you might as well just call `getRequestContent()` for the raw data string.
___
### Functions
##### > getFiles() : `\ArrayObject[]|Storage\UploadedFile[]`
Obtain an array containing all files with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to lookup files for.
`$useWrappers` | `bool` | Use KrameWork file wrappers.
##### > hasFile() : `bool`
Check if this request contains an uploaded file with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to check for.
##### > getQueryData() : `array`
Retrieve the decoded query string for this request.
##### > getQueryDataValue() : `mixed|null`
Retrieve a value from the query string with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key contained in the query string.
##### > getQueryDataValues() : `array`
Retrieve multiple values from the query string. Accepts a variable amount of string arguments.
##### > hasFormData() : `bool`
Check if this request contains form data. Occurs when content-type is multipart/form-data or application/x-www-form-urlencoded
##### > hasMultipartFormData() : `bool`
Check if this request contains multipart form data. Occurs when content-type is multipart/form-data
##### > getFormDataValue() : `mixed|null`
Retrieve a value from submitted form data with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to get value for.
##### > getFormDataValues() : `array`
Retrieve multiple values from submitted form data. Accepts variable amount of string arguments.
##### > getFormData() : `array`
Retrieve the content of the request as decoded form data. Result is stored in a key/value pair array. Array values (foo[]) are returned as arrays.
##### > getJSON() : `mixed|string`
Retrieve the request content as (optionally decoded) JSON. Skipping decode and content-type validation: use getRequestContent() instead.

parameter | type | description
--- | --- | ---
`$decode` | `bool` | Parse the string and validate it.
`$ignoreContentType` | `bool` | Skip validation of the content-type.
`$wrapper` | `bool` | Wrap the decoded JSON object in a JSONFile wrapper.

exception | reason
--- | ---
`InvalidRequestTypeException` | Content is not JSON.
##### > isSecure() : `bool`
Check if this request was made over https protocol.
##### > getRequestContent() : `string`
Retrieve the raw content of the request. Result is not parsed or validated.
##### > getContentLength() : `int`
Retrieve the length of the raw request content.
##### > getUserAgent() : `string`
Retrieve the user-agent string for the current request. Returns 'Unknown' if not available.
##### > getReferer() : `string`
Retrieve the referer URL for this request. Returns an empty string if not available.
##### > getContentType() : `string`
Retrieve the content-type of this request. Returns 'text/plain' if not available.

parameter | type | description
--- | --- | ---
`$parameters` | `bool` | Include content-type parameters.
##### > getRemoteAddress() : `string`
Retrieve the remote address for this request. Returns an empty string if not available.
##### > getRequestURI() : `string`
Retrieve the URI of this request (includes query string). Returns an empty string if not available.
##### > getQueryString() : `string`
Retrieve the query string used in this request. Returns an empty string if not available.
##### > getRequestMethod() : `string`
Retrieve the method of this request. Defaults to 'GET' if not available.

