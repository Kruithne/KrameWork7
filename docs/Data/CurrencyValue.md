## KrameWork\Data\CurrencyValue

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `CurrencyValue` class is a container class which provides features to deal with money data in reports.
___
### Examples
The value stored in the `CurrencyValue` class will automatically be converted to string when necessary.
```php
$value = new CurrencyValue(4.2);
print($value);
```
___
### Functions
##### > __construct() : `void`
CurrencyValue constructor.

parameter | type | description
--- | --- | ---
`$value` | `mixed|null` | Any value, will be cast to float automatically.


##### > real() : `float|null`
Returns the value passed to the constructor converted to a float.

##### > json() : `float|null`
Returns the value passed to the constructor converted to a float.
API for `JsonSerialization`.

##### > compare() : `int`
Compares the value to another value. Used in sorting operations.

parameter | type | description
--- | --- | ---
`$to` | `float|CurrencyValue|null` | Another value to compare with.
##### > __toString() : `string`
Returns the inner value converted to a string.
A null value will be converted to an empty string.