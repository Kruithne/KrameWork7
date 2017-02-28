## KrameWork\HTTP\WebRequest

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage of this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `WebRequest` class streamlines the ability to send HTTP(s) requests through various protocol methods using custom headers and content.
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
During default operation, no headers are set on the request, however we can set our own using the `addHeader()` function provided by the class. The function accepts a formatted header string, or an array of them.
```php
// Setting a header to use with the request.
$req->addHeader('Accept-language: en');

// Setting multiple headers to use with the request.
$req->addHeader(['Accept-language: en', 'Cookie: foo=bar']);

// Using KrameWork\HTTP headers.
// Note: Setting XSSProtectionHeader on a request makes no sense, this is an example, not a guide.
$req->addHeader(new XSSProtectionHeader());
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
`$headers` | `string|array` | Header string, or array of strings.

exception | reason
--- | ---
`InvalidHeaderException` | Header was not a string (or array of strings).

##### > send() : `bool`
Send the request. Return boolean indicates success.

##### > getResponse() : `string`
Get the response from this request.

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