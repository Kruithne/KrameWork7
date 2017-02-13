## KrameWork\Storage\Directory

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Constants** - Constants exposed from this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `Directory` class is designed to allow easy handling and management of directories. The class extends `DirectoryItem`, the same base class used by `File`; because of this, the functionality for this class is similar to that described in [File](File.md).
___
### Examples
For basic usage, simply create an instance of `DirectoryItem` with the first parameter to the constructor being the directory you wish to work with.
```php
$directory = new Directory("assets/images");
```
The directory separators used in the provided path do not need to match the environment, since KrameWork will automatically substitute the current operating-system standard before processing.

Attempts to read from a directory that does not exist, will throw an exception. It's best practice to check if the directory exists beforehand by calling `exists()`.
```php
$directory = new Directory("assets/images");
if ($directory->exists())
	// Fondle directory.
```
Similar to `File`, the `exists()` call will return `true` even if something that is not a directory exists at the given path. To ensure that the directory both exists and is indeed a directory, it's best to call `isValid()` instead.
```php
$directory = new Directory("assets/images");
if ($directory->isValid())
	// Fondle directory.
```
##### Directory Creation
By default, if the directory does not exist when you create a `Directory` instance, it will not be created. You can change this behavior by providing `true` to the second parameter of the constructor.
```php
$directory = new Directory("assets/images", true);
```
In the above example, if the directory `assets/images` does not exist, then it will be created. Directory creation using the constructor is equal to calling `create(true, 0777)` (see below for info), but since the return result is not provided, you should always confirm the directory was created.
```php
$directory = new Directory("assets/images", true);
if ($directory->isValid())
	// Our directory was created, or already existed!
```
As mentioned above, the other way to create a directory is by calling the `create(recursive, mode)` function on a `Directory` instance.

 - `recursive [bool]` - If true, all directories in the given path will be created if missing, as opposed to just the base. **Default: true**
 - `mode [int]` - Directory permissions on creation. No effect on Windows. **Default: 0777**
```php
$directory = new Directory("assets/images", false);
if (!$directory->create(false)) // Fails if `assets` directory is missing.
	// Unable to create directory.
```
##### Accessing Directories
A directory is not much use to us if we can't access the items within it; this can be done with a call to `getItems(flags)`. The `flags` parameter is a bit-masked value controlling the behavior of the call, the values of which are stored as constants on the `Directory` class.

 - `USE_WRAPPERS` - All returned directory items will be wrapped in `File`/`Directory` classes. Strings will be returned if disabled. **Default: enabled**.
 - `INCLUDE_FILES` - Include files in the result. **Default: enabled**.
 - `INCLUDE_DIRECTORIES` - Include directories in the result. **Default: enabled**.
 - `INCLUDE_HIDDEN` - Include hidden files/directories in the result. **Default: disabled**.
 - `INCLUDE_ALL` - Combines `INCLUDE_FILES`, `INCLUDE_DIRECTORIES` and `INCLUDE_HIDDEN`.
 - `RETURN_FULL_PATHS` - Return full paths rather than base names. Only effective if `USE_WRAPPERS` is disabled. **Default: disabled**.
 - `DEFAULT_FLAGS` - Default behavior.

The example below shows collecting all items from a `Directory` using the default behavior along with `INCLUDE_HIDDEN` being enabled.
```php
$dir = new Directory("assets/images");
$items = $dir->getItems(Directory::DEFAULT_FLAGS | Directory::INCLUDE_HIDDEN);
foreach ($items as $item)
	// $item is File or Directory, both extending DirectoryItem.
```
Two short-cut functions are provided by the `Directory` class for item retrieval, both of which are aliases for `getItems(flags)` with some forced flags:

 - `getFiles(flags)` - Forces `INCLUDE_FILES` enabled & `INCLUDE_DIRECTORIES` disabled.
 - `getDirectories(flags)` - Forces `INCLUDE_DIRECTORIES` enabled and `INCLUDE_FILES` disabled.

##### Sub-files/directories
The `Directory` class exposes the following functions for handling sub-files/directories:

 - `hasItem(name):bool` - Check if the directory contains a file or directory called `name`.
 - `hasFile(name):bool` - Check if the directory contains a file called `name`.
 - `hasDirectory(name):bool` - Check if the directory contains a directory called `name`.
 - `createDirectory(name, mode):Directory` - Attempt to create a sub-directory called `name` with `mode` permissions. `mode` has no effect on Windows. *
 - `createFile(name):File` - Attempt to create a sub-file called `name`. *

\* *Not intended for recursive creation; slashes cause undefined behavior.*

##### Deletion
You've decided you no longer like your directory, so let's delete it! A simple call to `delete()` will do the trick.
```php
$directory = new Directory("assets/images", false);
if (!$directory->delete())
	// Stubborn directory, could not delete.
```
One of the common causes for not being able to delete a directory, is that it has something inside of it! We can solve this by providing `true` to our `delete()` call, which will iterate over every file/directory within the directory and delete recursively from the bottom up.
```php
$directory = new Directory("assets/images", false);
if (!$directory->delete(true))
	// Permission error?
```
___
### Constants
Constants available in the `Directory` class:

constant | value | description
--- | --- | ---
`USE_WRAPPERS` | `0x1` | Directories/files returned will be in storage wrappers.
`INCLUDE_FILES` | `0x2` | Include files in the given retrieval call.
`INCLUDE_DIRECTORIES` | `0x4` | Include directories in the given retrieval call.
`INCLUDE_HIDDEN` | `0x8` | Include hidden items in the given retrieval call.
`RETURN_FULL_PATHS` | `0x10` | When using non-wrapper mode, return full paths.
`INCLUDE_ALL` | `*` | Alias for all `INCLUDE_` flags enabled.
`DEFAULT_FLAGS` | `*` | Alias for `USE_WRAPPERS`, `INCLUDE_FILES` and `INCLUDE_DIRECTORIES` enabled.
___
### Functions
##### > __construct() : `void`
Directory constructor.

parameter | type | description
--- | --- | ---
`$path` | `string` | Path to the directory.
`$create` | `bool` | Create directory if missing.

##### > isValid() : `bool`
Check if the directory exists and is valid.
##### > create() : `bool`
Attempt to create the directory.

parameter | type | description
--- | --- | ---
`$recursive` | `bool` | Create all directories in the path.
`$mode` | `int` | File permissions (No effect on Windows).

##### > delete() : `bool`
Attempt to delete the directory.

parameter | type | description
--- | --- | ---
`$recursive` | `bool` | Recursively delete the directory.

##### > getItems() : `string[]|DirectoryItem[]`
Retrieve a all items contained in the directory.

parameter | type | description
--- | --- | ---
`$flags` | `int` | Bit-mask flags to control retrieval.

exception | reason
--- | ---
`InvalidDirectoryException` | Directory does not exist or could not be accessed.

##### > getFiles() : `array`
Retrieve a list of all files in the directory. Alias of getItems() with INCLUDE_FILES and ~INCLUDE_DIRECTORIES.

parameter | type | description
--- | --- | ---
`$flags` | `int` | Bit-mask flags to control retrieval.
##### > getDirectories() : `array`
Retrieve a list of all directories in the directory. Alias of getItems() with INCLUDE_DIRECTORIES and ~INCLUDE_FILES.

parameter | type | description
--- | --- | ---
`$flags` | `int` | Bit-mask flags to control retrieval.
##### > hasItem() : `bool`
Check if this directory contains an item with the given name.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name to check the directory for.
##### > hasFile() : `bool`
Check if this directory contains a file with the given name. Mimics hasItem() with additional file type check.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the file to check for.
##### > hasDirectory() : `bool`
Check if this directory contains a directory with the given name. Mimics hasItem() with additional directory type check.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the directory to check for.
##### > createDirectory() : `Directory`
Create a new directory inside this directory. Directory creation is not guaranteed. Confirm with isValid() check on the returned Directory wrapper object.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the directory to create.
`$mode` | `int` | Permissions mode (No effect on Windows).

exception | reason
--- | ---
`FileAlreadyExistsException` | Directory already exists.
##### > createFile() : `File`
Create a new file inside this directory. File create is not guaranteed, confirm with isValid() check on the returned File wrapper object.

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the file to create.

exception | reason
--- | ---
`FileAlreadyExistsException` | File already exists.
