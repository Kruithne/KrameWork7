## KrameWork\Data\DateTimeValue

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `DateTimeValue` class is a container class which provides features to deal with timestamps in reports.
___
### Examples
The value stored in the `DateTimeValue` class will automatically be converted to string when necessary.
```php
$value = new DateTimeValue("1980-01-01 12:38");
print($value);
```
___
### Functions
##### > __construct() : `void`
DateTimeValue constructor.

parameter | type | description
--- | --- | ---
`$value` | `mixed|null` | Any value. Will be passed through `strtotime`


##### > real() : `int|null`
Returns the value passed to the constructor, converted into a unix timestamp.

##### > json() : `string|null`
Returns the value passed to the constructor, converted into an ISO 8601 timestamp.
API for `JsonSerialization`.

##### > compare() : `int`
Compares the value to another value. Used in sorting operations.

parameter | type | description
--- | --- | ---
`$to` | `mixed|null` | Another value to compare with.
##### > __toString() : `string`
Returns the inner value converted to a string.
A null value will be converted to an empty string.