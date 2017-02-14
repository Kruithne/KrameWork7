## KrameWork\Storage\JSONFile

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `JSONFile` class is an extension of the `File` class which provides more specific handling for JSON files, such as automatic decoding/encoding, and a stream-lined container for data access.
___
### Examples
##### Reading
Similar to the underlying `File` class, simply construct an instance and you're good to go.
```php
$json = new JSONFile("myData.json");
print($json->someValue); // Access JSON values instantly.
```
By default, the contents of the file will be loaded automatically. If the file does not exist, an exception will be thrown. If you'd rather check this, you can control auto-loading with the third construction parameter; check the [FileExample](FileExample.md) on handling that.

If you're looking for some of the functionality of the internal `ArrayObject` beyond the `get`, `set` and `unset` methods exposed through `JSONFile`, you can access the internal container by calling `getData()`.
```php
$json = new JSONFile("myData.json");
var_dump($json->getData());
```
Perhaps you want the raw JSON data before it was parsed? You can access that with the `getRawData()` function, which returns the JSON string loaded from the file.
```php
$json = new JSONFile("myData.json");
print($json->getRawData()); // JSON string
```
With this, you may also want to disable the use of the internal `ArrayObject` container, which can be done by provided `false` for `useContainer`, the second construction parameter; although at this point, you're better off using `File` ([FileExample](FileExample.md)) on its own.

##### Writing
Once you have a `JSONFile` object, you can assign values to it. If you want to persist those changes to the disk, call `save()`. Check the [FileExample](FileExample.md) document for more information and examples on file-saving.
```php
$json = new JSONFile("myData.json", true, false);
$json->myValue = true;
$json->save();
```
##### Decoding Options
Three functions are available to control the decoding options:

 - `setRecursionDepth(int)` - Sets the recursion depth for decoding. **Default: 512**
 - `setAssociative(bool)` - If set to true, an associative array will be returned rather than an object. No effect unless bypassing the internal `ArrayObject` of `JSONFile`. **Default: false**
 - `setOptions(int)` - Bit-mask flag of JSON options, such as `JSON_BIGINT_AS_STRING`.
 
Note: These should be set *before* the file is read, or they will have no effect.
```php
$json = new JSONFile("bigData.json", true, false); // No auto-load.
$json->setOptions(JSON_BIGINT_AS_STRING); // Convert big numbers.
$json->read();

$bigNumStr = $json->myBigNumber; // string
```
___
### Functions
##### > __construct() : `void`
JSONFile constructor.

parameter | type | description
--- | --- | ---
`$file` | `string` | Path to the file.
`$useContainer` | `bool` | Loaded/inserted data will be contained using an ArrayObject.
`$autoLoad` | `bool` | Attempt to read data from the file on instantiation.

exception | reason
--- | ---
`JSONException` | JSON error occured while decoding the file data.

##### > __get() : `mixed|null`
Obtain a value from the JSON container.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to lookup.

exception | reason
--- | ---
`JSONException` | Data container not initiated.
##### > __set() : `void`
Set the value of a specific key in the JSON container.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to store the value for.
`$value` | `mixed` | Value to store at the given key.

exception | reason
--- | ---
`JSONException` | Data container not initiated.
##### > __unset() : `void`
Remove a value from the JSON container with the given key.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to remove from the container.
##### > read() : `void`
Attempt to read and decode JSON data from the file. Read data is not returned, but made available through the wrapper.

exception | reason
--- | ---
`JSONException` | JSON error occured while reading the file data.
##### > save() : `void`
Save the contents of the JSON container to a file. Using an alternative path to save will not change the original path stored by the wrapper.

parameter | type | description
--- | --- | ---
`$file` | `string|null` | Path to save the file. If omitted, uses original file path.
`$overwrite` | `bool` | Overwrite file if it exists.

exception | reason
--- | ---
`JSONException` | JSON error occured while encoding the container data.

##### > getData() : `\ArrayObject|null|string`
Get the JSON container inside this wrapper. Returns a string if not using containers (see constructor).

parameter | type | description
--- | --- | ---
`$forceRead` | `bool` | Call read() if no data.
##### > setData() : `void`
Set the data contained by this wrapper. Not recommended unless not using containers (see constructor).

parameter | type | description
--- | --- | ---
`$data` | `mixed` | JSON object.

##### > getRawData() : `string|null`
Get the raw data string for this wrapper.
##### > setRawData() : `void`
Set the raw data string for this file.

parameter | type | description
--- | --- | ---
`$data` | `string` | Raw data.
##### > setRecursionDepth() : `void`
Set the recursion depth for file reading. Defaults to 512 if not set.

parameter | type | description
--- | --- | ---
`$depth` | `int` | Recursion depth.
##### > setAssociative() : `void`
Set if this file should read objects as associative arrays. Defaults to false if not set.

parameter | type | description
--- | --- | ---
`$assoc` | `bool` | Associative state.
##### > setOptions() : `void`
Set the JSON options bit-mask.

parameter | type | description
--- | --- | ---
`$mask` | `int` | Bit-mask options flag.
