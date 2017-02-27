## KrameWork\HTTP\HSTSHeader : HTTPHeader

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`HSTSHeader` is a class used to generate a HTTP Strict Transport Security header for use as a HTTP header. The class is intended to be used along-side the `HTTPHeaders` class, however can be used as a standalone if desired.
___
### Functions
##### > __construct() : `void`
HSTSHeader constructor.

parameter | type | description
--- | --- | ---
`$maxAge` | `int` | How long user-agents will redirect to HTTPS in seconds.
`$includeSubDomains` | `bool` | Upgrade requests for sub-domains.
`$preload` | `bool` | Include on HSTSHeader preload list.

##### > setMaxAge() : `void`
Set how long user-agents will redirect to HTTPS in seconds. Minimum accepted value by browsers is two months (63072000).

parameter | type | description
--- | --- | ---
`$maxAge` | `int` | Time period HTTPs will be supported.

##### > setIncludeSubDomains() : `void`
Upgrade requests for sub-domains.

parameter | type | description
--- | --- | ---
`$include` | `bool` | Include sub-domains.

##### > setPreload() : `void`
Include on HSTSHeader preload lists.

parameter | type | description
--- | --- | ---
`$preload` | `bool` | Add to preload lists.

##### > getFieldName() : `string`
Get the field name for this header.

##### > getFieldValue() : `string`
Get the field value for this header.

##### > apply() : `void`
Apply this header to the current response.

##### > __toString() : `string`
Get the compiled header string.