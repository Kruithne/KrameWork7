## KrameWork\API\reCAPTCHA

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage of the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `reCAPTCHA` class is an API class to interact with the third-party reCAPTCHA service.
This class depends on the HTTPContext class.
___
### Example
This is a basic example of how to use this class.
```php
try {
    $captcha = new reCAPTCHA('my_secret_api_key_from_google');
    if ($captcha->validate('response_token_from_client')) {
        // Captcha validated successfully.
    } else {
        // Invalid captcha.
    }
} catch (reCAPTCHAException $e) {
    print($e->getMessage()); // Error.
}
```
___
### Functions
##### > __construct() : `void`
reCAPTCHA constructor.

parameter | type | description
--- | --- | ---
`$secret` | `string` | Secret API key.

##### > setRemoteIP() : `void`
Manually set the remote IP to use.

parameter | type | description
--- | --- | ---
`$ip` | `string` | IP to use.

##### > validate() : `bool`
Attempt to validate a captcha response.

parameter | type | description
--- | --- | ---
`$token` | `string` | Token from the client.

exception | reason
--- | ---
`reCAPTCHAException` | Something went poop, check the message for details.