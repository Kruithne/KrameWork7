## HTTPContext
>- **Namespace**: KrameWork\HTTPContext
>- **File**: KrameWork7/src/HTTPContext.php

### General Usage
The desire behind the `HTTPContext` class is to provide a simple, stream-lined interface for obtaining headers and data from the current request. Below is a list of generic functions provided by an instance of this class; for more detailed functions, read further on.

 - `getRequestMethod()` - Get the current request method, such as `GET` or `POST`. **Default: `GET`**.
 - `getRemoteAddress()` - Get the remote address for the request. **Default: ""**.
 - `getRequestURI()` - Get the URI for this request. Contains query string. **Default: ""**.
 - `getQueryString()` - Get the raw query string for this request. **Default: ""**.
 - `getUserAgent()` - Get the user agent string for this request. **Default: "Unknown"**.
 - `getContentType()` - Get the content-type for this request. **Default: "text/plain"**.
 - `getContentLength()` - Get the length of the content for this request.
 - `getRequestContent()` - Get the raw content provided with this request. **Default: ""**.
 - `isSecure()`  - Check if this request was done via `https` protocol.

### JSON Requests
 If you're expecting the request as JSON, the `HTTPContext` class can automatically validate and parse it for you, providing you with a JSON object.
```php
$http = new HTTPContext();
$json = $http->getJSON(); // Hooray, JSON!
```
By default, the request data will be decoded, and the content-type header will be checked for `application/json`. If either of these fail, an `InvalidRequestTypeException` will be thrown. The behavior can be controlled by the two parameters for the call: `getJSON(bool $decode = true, bool $ignoreContentType = false);`.

Omitting both these checks will render the function redundant, and you might as well just call `getRequestContent()` for the raw data string.