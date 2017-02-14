## KrameWork\Storage\Session

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `Session` class provides an `IDataCache` compatible interface to the request session.
___
### Functions
##### > __construct() : `void`
Session constructor.

parameter | type | description
--- | --- | ---
`$autoStart` | `bool` | Start session on instantiation.
`$secure` | `bool` | Prevent session theft.

##### > start() : `void`
Attempt to start a session.

##### > flush() : `void`
Delete all existing data in the session and start a newly generated one.

##### > isActive() : `bool`
Check if a session is currently active.

##### > __get() : `mixed`
Retrieve a session value.

parameter | type | description
--- | --- | ---
`string` | `$name` | Key to retrieve from the session.

##### > __set() : `void`
Set a session value.

parameter | type | description
--- | --- | ---
`string` | `$name` | Key to set the value with.
`mixed` | `$value` | Value to store in the session.

##### > __unset() : `void`
Unset a session value.

parameter | type | description
--- | --- | ---
`string` | `$name` | Key to remove from the session.

##### > store() : `void`
Store a value in the cache.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to store the value under.
`$value` | `mixed` | Value to store in the cache.
`$expire` | `int` | No effect for this implementation.

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