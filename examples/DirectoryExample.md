## Directory
>- **Namespace**: KrameWork\Storage\Directory
>- **File**: KrameWork7/src/Storage/Directory.php

### Basic Usage
The `Directory` class extends `DirectoryItem`, the same base class used by `File`; because of this, the functionality for this class is similar to that described in [FileExample](FileExample.md).

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
### Directory Creation
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
### Accessing Directories
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

### Sub-files/directories
The `Directory` class exposes the following functions for handling sub-files/directories:

 - `hasItem(name):bool` - Check if the directory contains a file or directory called `name`.
 - `hasFile(name):bool` - Check if the directory contains a file called `name`.
 - `hasDirectory(name):bool` - Check if the directory contains a directory called `name`.
 - `createDirectory(name, mode):Directory` - Attempt to create a sub-directory called `name` with `mode` permissions. `mode` has no effect on Windows. *
 - `createFile(name):File` - Attempt to create a sub-file called `name`. *

\* *Not intended for recursive creation; slashes cause undefined behavior.*

### Deletion
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