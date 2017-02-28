## KrameWork\HTTP\JsonRequest

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage of this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `JsonRequest` class builds on the `WebRequest` class to enable quick and easy access to JSON based web APIs.
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

### Functions
##### > __construct() : `void`
WebRequest constructor.

parameter | type | description
--- | --- | ---
`$url` | `string` | Request endpoint.
`$method` | `string` | Request method (use class constants).

##### > postJson() : `bool`
Send the request. Return boolean indicates success.

parameter | type | description
--- | --- | ---
`$object` | `mixed` | Data to json encode and post to the server API.

##### > getResponse() : `string`
Get the response from this request decoded from JSON.
