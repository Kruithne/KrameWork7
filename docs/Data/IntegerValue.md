## KrameWork\Data\IntegerValue

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `IntegerValue` class is a container class which provides features to deal with integer data in reports.
___
### Examples
The value stored in the `IntegerValue` class will automatically be converted to string when necessary.
```php
$value = new IntegerValue(42);
print($value);
```
___
### Functions
##### > __construct() : `void`
IntegerValue constructor.

parameter | type | description
--- | --- | ---
`$value` | `mixed|null` | Any value, will be converted to integer.


##### > real() : `int|null`
Returns the value passed to the constructor as an integer.

##### > json() : `int|null`
Returns the value passed to the constructor as an integer.
API for `JsonSerialization`.

##### > compare() : `int`
Compares the value to another value. Used in sorting operations.

parameter | type | description
--- | --- | ---
`$to` | `int|IntegerValue|null` | Another value to compare with.
##### > __toString() : `string`
Returns the inner value converted to a string.
A null value will be converted to an empty string.