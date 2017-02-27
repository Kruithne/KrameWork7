## KrameWork\HTTP\XFrameHeader : HTTPHeader

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`XFrameHeader` is a class used to generate a header for enabling and controlling the framing directive. The class is intended to be used along-side the `SecurityPolicy` class, however can be used as a standalone if desired.
___
### Functions
##### > __construct() : `void`
XFrameHeader constructor.

parameter | type | description
--- | --- | ---
`$option` | `string` | Framing option for this header.

##### > setOption() : `void`
Set the framing option for this header. Use the constants provided by the XFrameHeader class.

parameter | type | description
--- | --- | ---
`$option` | `string` | Directive for this header.

##### > getFieldName() : `string`
Get the field name for this header.

##### > getFieldValue() : `string`
Get the field value for this header.

##### > apply() : `void`
Apply this header to the current response.

##### > __toString() : `string`
Get the compiled header string.