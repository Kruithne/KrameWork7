## KrameWork\Storage\MemoryFile

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `MemoryFile` class is a bare-bones implementation of the `File` class which allows for dynamic files to be created at runtime and handled as if they were a normal file.

During default operation, the data contained in a `MemoryFile` instance is not stored anywhere and will be lost once the object is left to garbage collection, however the contents can be saved manually if needed using the `save()` call.
___
### Examples
Usage example of the `MemoryFile` class:
```php
$data = json_encode([1, 2, 3, 4]);
$file = new MemoryFile('exmp.json', $data, 'application/json');
// This can now be treated like a file, such as attaching it to a KrameWork\Mailing\Mail object!
```
___
### Functions
##### > __construct() : `void`
MemoryFile constructor. If an instance of File is provided as $source, the state of the provided instance will be cloned to this one. Both $autoLoad and $touch will be ignored in that scenario.

parameter | type | description
--- | --- | ---
`$source` | `string||File` | Path to the file, or another File instance to clone.
`$content` | `string` | Data for the file.
`$contentType` | `string` | Data content-type.
##### > isValid() : `bool`
Check if the file exists and is valid. A MemoryFile is valid until delete() is called.
##### > getSize() : `int`
Get the size of this file in bytes.
##### > getFileType() : `string`
Get the content type of this file.
##### > getData() : `null|string`
Get the data contained in this memory file.

parameter | type | description
--- | --- | ---
`$forceRead` | `bool` | No effect, included for implementation sake.
##### > setData() : `void`
Set the data contained in this memory file.

parameter | type | description
--- | --- | ---
`$data` | `mixed` | Data to store in the wrapper.
##### > delete() : `bool`
'Delete' the memory file, erasing the content from the container and marking the instance as invalid.
##### > getBase64Data() : `string`
Retrieve the raw data of this file encoded as base64.

parameter | type | description
--- | --- | ---
`$forceRead` | `bool` | No effect, included for implementation sake.