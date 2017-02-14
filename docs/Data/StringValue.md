## KrameWork\Data\StringValue

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `StringValue` class is a container class which provides features to deal with string data in reports.
___
### Examples
The value stored in the `StringValue` class will automatically be converted to string when necessary.
```php
$value = new Value("some data");
print($value);
```
___
### Functions
##### > __construct() : `void`
StringValue constructor.

parameter | type | description
--- | --- | ---
`$value` | `string|null` | Any value.


##### > real() : `string|null`
Returns the string value.

##### > json() : `string|null`
Returns the string value.
API for `JsonSerialization`.

##### > compare() : `int`
Compares the value to another value with `strnatcasecmp`. Used in sorting operations.

parameter | type | description
--- | --- | ---
`$to` | `string|StringValue|null` | Another value to compare with.
##### > __toString() : `string`
Returns the inner value converted to a string.
A null value will be converted to an empty string.