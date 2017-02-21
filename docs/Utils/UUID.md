## KrameWork\Utils\UUID

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage of the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`UUID` is a static class that provides generation of RFC 4122 compliant v3, v4 and v5 UUIDs.
___
### Example
Below are some examples of how this class works.
```php
// RFC 4211 compliant namespace.
$namespace = '7965109e-f850-11e6-bc64-92361f002671';

// Check our namespace UUID is valid.
print(UUID::isValid($namespace) . PHP_EOL); // > true

// Generate a v3 (namespace based) UUID.
print(UUID::generate_v3($namespace, 'foo') . PHP_EOL); // > f0778395-cc05-369b-9002-592c5902f4f5

// Generate a v5 (namespace based) UUID.
print(UUID::generate_v5($namespace, 'foo') . PHP_EOL); // > 33b80aa7-3459-5b8f-bb2e-f6cc60ba30d4

// UUID generation will always provide a valid UUID, even when the
// namespace was invalid. To ensure validity, compare with UUID::NIL.

// Check generated UUID errors with UUID::NIL.
$uuid = UUID::generate_v3('invalidNamespace', 'bar');
print($uuid == UUID::NIL ? 'Invalid UUID' : 'Valid UUID'); // > Invalid UUID

// Generate a v4 (pseudo-random) UUID.
print(UUID::generate_v4() . PHP_EOL); // > 5665420c-3b97-4c52-a4f0-07c4752390da
```
___
### Functions
##### > generate_v3() : `string`
Generate an RFC 4122 compliant v3 (namespace based) UUID. Returns false when given an invalid namespace.

parameter | type | description
--- | --- | ---
`$namespace` | `string` | UUID namespace.
`$name` | `string` | UUID name.

##### > generate_v4() : `string`
Generate an RFC 4122 compliant v4 (pseudo-random) UUID.

##### > generate_v5() : `string`
Generate an RFC 4122 compliant v5 (namespace based) UUID. Returns false when given an invalid namespace.

parameter | type | description
--- | --- | ---
`$namespace` | `string` | UUID namespace.
`$name` | `string` | UUID name.

##### > isValid() : `bool`
Check if the given UUID is RFC 4122 compliant.

parameter | type | description
--- | --- | ---
`$uuid` | `string` | UUID to validate.
