## KrameWork\HTTP\Request\JSONRequest : WebRequest

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage of the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `JSONRequest` class is a basic extension of the `WebRequest` class which attempts to automatically decode the response as a JSON object when `getResponse()` is called. It's worth noting that `__toString()` will return the raw response data.
___
### Example
Sending a request using this class is straight-forward, take a look at the example below for the most basic usage.
```php
$req = new JsonRequest('http://hipsterjesus.com/api/');
if ($req->send())
    var_dump($req->getResponse());

// > object(stdClass)#1 (2) {
//     ["text"]=>string(2502) "<p>Banh mi chicharrones cred aute...
```
The raw response of the request can be obtained by invoking the `__toString()` magic method on the object. Trying to obtain the response before calling `send()`, or if `send()` returns `false`, will result in a `null` (or empty string if using `__toString`).

Posting some object to the API;
```php
$req = new JsonRequst('https://supersecret.example.com/api/magical', WebRequest::METHOD_POST);
if ($req->postJson(['something' => 'else']))
    var_dump($req->getResponse());
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

##### > postJson() : `bool`
Post an object encoded as json with the request. Return boolean indicates success.

parameter | type | description
--- | --- | ---
`$object` | `mixed` | Data to json encode and post to the server API.

exception | reason
--- | ---
`InvalidMethodException` | Not using METHOD_POST for a JSON-body request.

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