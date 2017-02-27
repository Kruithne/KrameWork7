## KrameWork\HTTP\HTTPHeaders

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `HTTPHeaders` class is intended to encompass control and generation of commonly used HTTP headers. The object provides a `compile()` function, which should be called before caching the object. Converting the object to a string (`__toString()`) will also return an encoded version after compilation, which can be fed into the constructor to skip the need to compile/provide headers.

It is recommended to use this class for headers you intend to set often, not one-time/responsive headers.
___
### Example
Here is a basic example of how to use this class. It's worth nothing this example doesn't delve deep into methods of caching, since it may differ on your application.
```php
$policy = new HTTPHeaders();
$policy->add(new XSSProtectionHeader());
$policy->add(new MIMEValidationHeader());
$policy->add(new XFrameHeader(XFrameHeader::DENY));
$policy->add(new HSTSHeader(63072000, true, true));
$policy->add(new CSPHeader([
    CSPHeader::DIRECTIVE_DEFAULT => [CSPHeader::SOURCE_HTTPS, CSPHeader::SOURCE_SELF],
    CSPHeader::DIRECTIVE_SCRIPT => ['https://cdnjs.cloudflare.com', 'https://www.google.com/'],
    CSPHeader::DIRECTIVE_FONT => 'https://fonts.googleapis.com/',
    CSPHeader::DIRECTIVE_STYLE => [CSPHeader::SOURCE_INLINE, 'https://cdnjs.cloudflare.com/'],
    CSPHeader::DIRECTIVE_OBJECT => CSPHeader::SOURCE_NONE,
    CSPHeader::DIRECTIVE_CHILD => CSPHeader::SOURCE_NONE
]));
$policy->compile();

// Now that the policy is compiled, you can (see: should) cache either the instance, or the result of `__toString()` which can be passed into the constructor of the class and skipping everything above.

$policy->apply(); // Set all HTTP headers with the policy set-up.
```
___
### Functions
##### > __construct() : `void`
HTTPHeaders constructor.

parameter | type | description
--- | --- | ---
`$input` | `array|string` | Policy array (header objects) or pre-compiled string.

##### > add() : `void`
Add a header to this policy.

parameter | type | description
--- | --- | ---
`$header` | `HTTPHeader` | Header to add.

##### > apply() : `void`
Apply this policy, compiling and setting all headers. Unless providing a pre-compiled string, call compile() first.

##### > compile() : `void`
Compiled all headers in this policy, storing them internally. Call before applying a non pre-compiled policy.

##### > __toString() : `void`
Get a serialized representation of this policy. Results may be incorrect if called before compile().

##### > generateApacheConfig() : `string`
Generate Apache configuration using these headers. Will not work with pre-compiled headers. Call compile() first.

parameter | type | description
--- | --- | ---
`$file` | `string|null` | Optional file to write out to.