## KrameWork\HTTP\HTTPContext

***Table of Contents***
* **Examples** - Usage examples.
* **Constants** - Constants exposed by this class.
* **Functions** - Comprehensive list of all functions in the class.
___
### Examples
##### JSON Requests
 If you're expecting the request as JSON, the `HTTPContext` class can automatically validate and parse it for you, providing you with a JSON object.
```php
$json = HTTPContext::getJSON(); // Hooray, JSON!
```
By default, the request data will be decoded, and the content-type header will be checked for `application/json`. If either of these fail, an `InvalidRequestTypeException` will be thrown. The behavior can be controlled by the two parameters for the call: `getJSON(bool $decode = true, bool $ignoreContentType = false);`.

Omitting both these checks will render the function redundant, and you might as well just call `getRequestContent()` for the raw data string.
___
### Functions
##### > HTTPContext::getClientIP() : `string|null`
Obtain the connecting clients IP, if available.
Note that this can include the remote port, as some proxies add it to X-Forwarded-For.

##### > HTTPContext::getFiles() : `\ArrayObject[]|Storage\UploadedFile[]`
Obtain an array containing all files with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to lookup files for.
`$useWrappers` | `bool` | Use KrameWork file wrappers.
##### > HTTPContext::hasFile() : `bool`
Check if this request contains an uploaded file with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to check for.
##### > HTTPContext::getQueryData() : `array`
Retrieve the decoded query string for this request.
##### > HTTPContext::getQueryDataValue() : `mixed|null`
Retrieve a value from the query string with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key contained in the query string.
##### > HTTPContext::getQueryDataValues() : `array`
Retrieve multiple values from the query string. Accepts a variable amount of string arguments.
##### > HTTPContext::hasFormData() : `bool`
Check if this request contains form data. Occurs when content-type is multipart/form-data or application/x-www-form-urlencoded
##### > HTTPContext::hasMultipartFormData() : `bool`
Check if this request contains multipart form data. Occurs when content-type is multipart/form-data
##### > HTTPContext::getFormDataValue() : `mixed|null`
Retrieve a value from submitted form data with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to get value for.
##### > HTTPContext::getFormDataValues() : `array`
Retrieve multiple values from submitted form data. Accepts variable amount of string arguments.
##### > HTTPContext::getFormData() : `array`
Retrieve the content of the request as decoded form data. Result is stored in a key/value pair array. Array values (foo[]) are returned as arrays.
##### > HTTPContext::getJSON() : `mixed|string`
Retrieve the request content as (optionally decoded) JSON. Skipping decode and content-type validation: use getRequestContent() instead.

parameter | type | description
--- | --- | ---
`$decode` | `bool` | Parse the string and validate it.
`$ignoreContentType` | `bool` | Skip validation of the content-type.
`$wrapper` | `bool` | Wrap the decoded JSON object in a JSONFile wrapper.

exception | reason
--- | ---
`InvalidRequestTypeException` | Content is not JSON.
##### > HTTPContext::isSecure() : `bool`
Check if this request was made over https protocol.
##### > HTTPContext::getRequestContent() : `string`
Retrieve the raw content of the request. Result is not parsed or validated.
##### > HTTPContext::getContentLength() : `int`
Retrieve the length of the raw request content.
##### > HTTPContext::getUserAgent() : `string`
Retrieve the user-agent string for the current request. Returns 'Unknown' if not available.
##### > HTTPContext::getReferrer() : `string`
Retrieve the referrer URL for this request. Returns an empty string if not available.
##### > HTTPContext::getContentType() : `string`
Retrieve the content-type of this request. Returns 'text/plain' if not available.

parameter | type | description
--- | --- | ---
`$parameters` | `bool` | Include content-type parameters.
##### > HTTPContext::getRemoteAddress() : `string`
Retrieve the remote address for this request. Returns an empty string if not available.
##### > HTTPContext::getRequestURI() : `string`
Retrieve the URI of this request (includes query string). Returns an empty string if not available.
##### > HTTPContext::getQueryString() : `string`
Retrieve the query string used in this request. Returns an empty string if not available.
##### > HTTPContext::getRequestMethod() : `string`
Retrieve the method of this request. Defaults to 'GET' if not available.
##### > HTTPContext::getRequestHeaders() : `array`
Retrieves the headers sent with this request. All header names are converted to lower-case regardless
of how they were sent.

On Apache, this makes use of the `apache_request_headers` function. If that function
cannot be found, the `$_SERVER` super-global is iterated for `HTTP_` prefixed keys instead.
##### > HTTPContext::getRequestHeader() : `mixed|null`
Retrieves a specific request header or returns NULL if the header was not sent.

parameter | type | description
--- | --- | ---
`$key` | `string` | Header name to lookup.