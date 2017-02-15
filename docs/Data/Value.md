## KrameWork\Data\Value

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `Value` class is a base class which provides features to deal with data in reports.
___
### Examples
The value stored in the `Value` class will automatically be converted to string when necessary.
```php
$value = new Value("some data");
print($value);
```
___
### Functions
##### > __construct() : `void`
Value constructor.

parameter | type | description
--- | --- | ---
`$value` | `mixed|null` | Any value.


##### > real() : `mixed|null`
Returns the value passed to the constructor.

##### > json() : `mixed|null`
Returns the value passed to the constructor.
API for `JsonSerialization` in derived classes.

##### > compare() : `int`
Compares the value to another value using `strnatcasecmp`. Used in sorting operations.

parameter | type | description
--- | --- | ---
`$to` | `int|Value|null` | Another value to compare with.
##### > __toString() : `string`
Returns the inner value converted to a string.
A null value will be converted to an empty string.