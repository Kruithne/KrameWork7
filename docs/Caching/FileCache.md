## KrameWork\Caching\FileCache : IDataCache

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
> **Warning**: This is not a cache for files, rather a cache stored in a file. This is a proof-of-concept implementation of the `IDataCache` interface and not intended for production use!

As stated above, the `FileCache` is a proof-of-concept implementation of the `IDataCache` interface which stores all of the data in a flat-file specified to the constructor. It provides full coverage of functionality defined in the `IDataCache` interface, including value expiry.

___
### Examples
Below is a basic example of how to use the class. For a full overview of provided methods, check the function list at the bottom of the document.
```php
$cache = new FileCache('myCache.dat');
$cache->foo = 42;

// Another request/execution, sometime later.
$cache = new FileCache('myCache.dat');
print($cache->foo); // > 42
```
___
### Functions
##### > __construct() : `void`
FileCache constructor.

parameter | type | description
--- | --- | ---
`$file` | `string` | File for the cache to read/write from.
`$autoPersist` | `bool` | Should the cache auto-persist to the file.
`$cleanOnLoad` | `bool` | Expired values will be cleaned on cache load.

exception | reason
--- | ---
`InvalidFileException` | File could not be found/opened.

##### > persist() : `void`
Persist the cache state to disk.

##### > __get() : `mixed|null`
Obtain a value from the cache. Returns null if the item does not exist.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key of the value.

##### > __set() : `void`
Store a value in the cache. Value will not expire, use store() if needed.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to store the value under.
`$value` | `mixed` | Value to store in the cache.

##### > store() : `void`
Store a value in the cache.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to store the value under.
`$value` | `mixed` | Value to store in the cache.
`$expire` | `int` | 60 *60 * 24 * 30 >= Unix Timestamp, otherwise seconds. 0 = Never.

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

