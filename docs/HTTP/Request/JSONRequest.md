## KrameWork\HTTP\Request\JSONRequest : WebRequest

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `JSONRequest` class is a basic extension of the `WebRequest` class which attempts to automatically decode the response as a JSON object when `getResponse()` is called. It's worth noting that `__toString()` will return the raw response data.
___
### Functions
##### > __construct() : `void`
WebRequest constructor.

parameter | type | description
--- | --- | ---
`$url` | `string` | Request endpoint.
`$method` | `string` | Request method (use class constants).

##### > addHeader() : `void`
Add a header to this request.

parameter | type | description
--- | --- | ---
`$headers` | `string|array` | Header string, or array of strings.

exception | reason
--- | ---
`InvalidHeaderException` | Header was not a string (or array of strings).

##### > send() : `bool`
Send the request. Return boolean indicates success.

##### > getResponse() : `mixed`
Get the JSON decoded response from this request. Returns null if unable to decode response.

exception | reason
--- | ---
`ResponseNotAvailableException` | Request not sent or failed.

##### > success() : `bool|null`
True/false depending on success of request. Returns null if request has not yet been sent.

##### > __toString() : `string`
Return the response for this request.

exception | reason
--- | ---
`ResponseNotAvailableException` | Request not sent or failed.