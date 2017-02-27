## KrameWork\HTTP\ReferrerPolicyHeader : HTTPHeader

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`ReferrerPolicyHeader` is a class used to generate a header for enabling and controlling the referrer policy header introduced in 2017. The class is intended to be used along-side the `HTTPHeaders` class, however can be used as a standalone if desired.
___
### Functions
##### > __construct() : `void`
ReferrerPolicyHeader constructor.

parameter | type | description
--- | --- | ---
`$value` | `string` | Initial value, use class constants.

##### > setValue() : `void`
Set the value of this header. Use the constants provided by the ReferrerPolicyHeader class.

parameter | type | description
--- | --- | ---
`$value` | `string` | Value for the header.

##### > getFieldName() : `string`
Get the field name for this header.

##### > getFieldValue() : `string`
Get the field value for this header.