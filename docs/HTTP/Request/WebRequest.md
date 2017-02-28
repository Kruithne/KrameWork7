## KrameWork\HTTP\Request\WebRequest

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage of this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `WebRequest` class streamlines the ability to send HTTP(s) requests through various protocol methods using custom headers and content. Below, the behavior of each request method is out-lined.

##### WebRequest::METHOD_POST
Content will be encoded and provided as HTTP request-content.

The following headers will be set:
header | value
--- | ---
`Content-Length` | Set to the length of the content after encoding.
`Content-Type` | Set to `application/x-www-form-urlencoded`

##### WebRequest::METHOD_GET
Content is encoded and formatted into a query-string, which will be appended to the end of the URL. If the provided URL already has query-string parameters, this will not break and the encoded values will simply be included. No HTTP content is provided with this method.

The following headers will be set:
header | value
--- | ---
`Content-Type` | Set to `application/x-www-form-urlencoded`
___
### Example
Sending a request using this class is straight-forward, take a look at the example below for the most basic usage.
```php
$req = new WebRequest('http://hipsterjesus.com/api/');
if ($req->send())
    var_dump($req->getResponse());

// > {"text":"<p>Tousled crucifix humblebrag nulla, distillery delectus...
```
The response of the request can also be obtained by invoking the `__toString()` magic method on the object, as will be shown in the next example. Trying to obtain the response before calling `send()`, or if `send()` returns `false`, will result in a `null` (or empty string if using `__toString`).

By default, the class uses the `GET` method, however we can change that using the second parameter of the constructor. It's recommended to use the `METHOD_` constants provided by the `WebRequest` class for this.
```php
$req = new WebRequest('http://hipsterjesus.com/api/', WebRequest::METHOD_POST);
if ($req->send())
    echo $req;
    
// > {"text":"<p>Art party DIY nisi four dollar toast.  Duis  portland ethical...
```
##### Headers
Some default headers are set, such as `Content-length`, however you can over-write these and set your own in various different ways.
```php
// Setting a header to use with the request.
$req->addHeader('Accept-language', 'en');

// Setting multiple headers to use with the request.
$req->addHeaders([
    'Accept-language' => 'en',
    'Cookie' => 'foo=bar'
]);

// Using KrameWork\HTTP headers.
// Note: Setting XSSProtectionHeader on a request makes no sense, this is an example, not a guide.
$req->addHeaderObject(new XSSProtectionHeader());
```
##### Content
What's the point of sending a request if we can't provide any content with it? Luckily, we can. The `WebRequest` object allows data to be set using the `__set()` magic method.
```php
$req = new WebRequest('http://hipsterjesus.com/api/');
$req->paras = 1; // (API) Paragraph count.
$req->html = false; // (API) Strip HTML tags from response.
if ($req->send())
    var_dump($req->getResponse());

// > {"text":"Mlkshk humblebrag cliche messenger bag skateboard.  In  farm...
```
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
`$fieldName` | `string` | Field name of the header.
`$fieldValue` | `string` | Field value of the header.

##### > addHeaderObject() : `void`
Add a HTTP header object to the request.

parameter | type | description
--- | --- | ---
`$header` | `HTTPHeader` | Header object to add.

##### > addHeaders() : `void`
Add multiple headers to the request. Array must be in fieldName => fieldValue format.

parameter | type | description
--- | --- | ---
`$headers` | `array` | Array of headers to add.

##### > hasHeader() : `bool`
Check if this request has a header set.

parameter | type | description
--- | --- | ---
`$checkName` | `string` | Field name to check for.

##### > send() : `bool`
Send the request. Return boolean indicates success.

##### > getResponse() : `string`
Get the response from this request.

exception | reason
--- | ---
`ResponseNotAvailableException` | ???

##### > success() : `bool|null`
True/false depending on success of request. Returns null if request has not yet been sent.

##### > __toString() : `string`
Return the response for this request.

exception | reason
--- | ---
`ResponseNotAvailableException` | Request not sent (or failed).