## KrameWork\Caching\Memcached

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `Memcached` class provided by KW7 is a basic interface for Memcached which conforms to the `IDataCache` interface, providing compatibility with other caching solutions provided in the same namespace.
> **Note**: Usage of this class requires a Memcached server set-up, running and accessible to the server which is executing your PHP script. Failure to do so will result in demons, lots of demons.

> **Note**: Memcached works with shared memory, meaning data stored within will be accesible to every request, and even other processes; it is not restricted to a specific user like sessions are. **Don't let sensitive data leak!**
___
### Examples
Below is a basic example of how to use the class. For a full overview of provided methods, check the function list at the bottom of the document.
```php
$cache = new Memcached(); // Defaults to 127.0.0.1:11211
$cache = new Memcached('192.168.1.5', 11222); // Non-standard address/port.

$cache->foo = 'bar'; // Store string 'bar' under key 'foo' using magic setter.
print($cache->foo); // Retrieve the same value using magic getter. Prints 'bar'.

$cache->store('foo', 'bar'); // Equivalent to the previous store operation above.
$cache->store('foo', 'bar', 60); // Same again, but value is only stored for 60 seconds.

unset($cache->foo); // Manually unset the 'foo' key.
$cache->flush(); // Delete everything in the cache!
```
___
### Functions
##### > __get() : `mixed|null`
Obtain a value from the cache. Returns null if the item does not exist.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key of the value.
##### > store() : `void`
Store a value in the cache.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to store the value under.
`$value` | `mixed` | Value to store in the cache.
`$expire` | `int` | 60*60*24*30 >= Unix Timestamp, otherwise seconds. 0 = Never.
##### > __set() : `void`
Store a value in the cache. Value will not expire, use store() if needed.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to store the value under.
`$value` | `mixed` | Value to store in the cache.
##### > __unset() : `void`
Remove an item stored in the cache.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key of the item to remove.
##### > flush() : `void`
Flush the cache, removing all stored data.
##### > increment() : `void`
Increase a numeric value in the cache.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key of the value.
`$weight` | `int` | How much to increment the value.
##### > decrement() : `void`
Decrease a numeric value in the cache.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key of the value.
`$weight` | `int` | How much to decrement the value.
