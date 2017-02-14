## KrameWork\Storage\File

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `File` class is designed to allow easy handling and management of files. There are various extensions of the class which allow more specific use-cases; check out the directory for other implementations.
___
### Examples
Basic usage:
```php
$file = new File("myFile.txt");
var_dump($file->getData());
```
Ideally, unless we're sure the file exists, we'll want to check that it does before we read the data from it. This can be achieved by passing `autoLoad` as `false` and checking `exists()` ourselves.
```php
$file = new File("myFile.txt");
if ($file->exists())
	// Fondle data.
```
Another way to achieve this using exceptions would be to catch the exceptions that are thrown from the instance construction if the file does not exist.
```php
try {
	$file = new File("myFile.txt");
	// Fondle data.
} catch (FileNotFoundException $e) {
	// File does not exist, or could not be accessed.
} catch (FileReadException $e) {
	// File could not be read, permission error?
}
```
Something to note, `exists()` will return `true` if something exists at the given path, even if that something is not a file. It's ideal to be more strict with this check, and use `isValid()`, which confirms existence as well as checking the file is indeed a file, followed by a call to `read()`.
```php
$file = new File("myFile.txt");
if ($file->isValid()) {
	$file->read();
	// Fondle data.
}
```
##### Writing/Saving
Writing our own data to the file wrapper is as simply as calling `setData()` on the wrapper. This function takes any kind of object, but will attempt to cast it to a `string` later on, so make sure it's stringable! Once we've set the data, we can persist it to file using `save()`.
```php
$file = new File("myNewFile.txt", false, true);
$file->setData("Hello, world!");
$file->save();
```
Note the the third parameter, `touch`, is passed as true in the above example. As outlined in the first paragraph of this document, this will cause the file to be created if it does not yet exist.

It's possible that we might want to save the data to another file, rather than the one we read from. This can easily be achieved by using the first parameter for `save()`, which expects a string; a file location to write to.
```php
$file = new File("sourceData.txt", true);
$file->save("targetData.txt");
```
If the place we're trying to write to already exists, you'll end up with a `FileWriteException` being thrown, this helps prevent writing over files we didn't expect to. In the case that you do want to overwrite a file, simply provide `true` to the second parameter of `save()`.
```php
$file = new File("existingData.txt", true);
$file->setData("New data!");
$file->save("existingData.txt", true); // Overwrite!
```
##### Deleting
Deleting a file is as simple as telling it to be deleted! No error will be thrown if the file does not exist; you can check before-hand with `exists()` or `isValid()` if needed.
```php
$file = new File("someFile.txt", false);
$file->delete();
```
___
### Functions
##### > __construct() : `void`
File constructor. If an instance of File is provided as $source, the state of the provided instance will be cloned to this one. Both $autoLoad and $touch will be ignored in that scenario.

parameter | type | description
--- | --- | ---
`$source` | `string||File` | Path to the file, or another File instance to clone.
`$autoLoad` | `bool` | Attempt to load the file contents on instantiation.
`$touch` | `bool` | Touch the file, creating it if missing.

exception | reason
--- | ---
`FileNotFoundException` | Specified file could not be found.
`FileReadException` | Specified file could not be read (permission error?).
##### > isValid() : `bool`
Check if the file exists and is valid.
##### > delete() : `bool`
Attempt to delete the file.
##### > getSize() : `int`
Get the size of this file in bytes.
##### > getExtension() : `string`
Get the extension of this file (without leading period).
##### > getFileType() : `string`
Attempts to get the MIME type of a file. Requires php_fileinfo extension to be enabled. Returns 'unknown' on failure.
##### > read() : `void`
Attempt to read the data from a file. Read data is not returned, but available through the wrapper.

exception | reason
--- | ---
`FileNotFoundException` | Specified file could not be found.
`FileReadException` | Specified file could not be read (permission error?).
##### > save() : `void`
Attempt to save the data in the wrapper to a file. Using an alternative path to save will not change the original path stored by the wrapper.

parameter | type | description
--- | --- | ---
`$file` | `string|null` | Path to save the file. If omitted, will use wrapper path.
`$overwrite` | `bool` | Overwrite the file if it exists.

exception | reason
--- | ---
`FileWriteException` | File already exists and overwrite not specified.

##### > getData() : `null|string`
Get the data contained by the wrapper after a read() or manual set.

parameter | type | description
--- | --- | ---
`$forceRead` | `bool` | Call read() if data is missing.
##### > getBase64Data() : `string`
Retrieve the raw data of this file encoded as base64.

parameter | type | description
--- | --- | ---
`$forceRead` | `bool` | Call read() if data is missing.
##### > setData() : `void`
Set the data for this file wrapper. Overwrites existing.

parameter | type | description
--- | --- | ---
`$data` | `mixed` | Data to store in the wrapper.

##### > marshalFrom() : `void`
Copy the state of another File instance to this instance.

parameter | type | description
--- | --- | ---
`$file` | `File` | `File` instance to copy from.