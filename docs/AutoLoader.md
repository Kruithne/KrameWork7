## KrameWork\AutoLoader

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Constants** - Constants exposed by this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`AutoLoader` is one of the primary classes, and provides dynamic auto-loading functionality with ease. It is highly recommended **not** to use this with another auto-loader; the class can be disabled at runtime if needed.
___
### Examples
##### Basic Usage
Setting up the auto-loader is as simple as creating a new instance of it.
```php
new AutoLoader();
```
Initiating the auto-loader without any parameters, will adopt the following default behavior:
- [x] Files inside of the working directory will be sourced for auto-loading `[INCLUDE_WORKING_DIRECTORY]`.
- [x] Files inside of the KrameWork directory will be sourced for auto-loading `[INCLUDE_KRAMEWORK_DIRECTORY]`.
- [x] Auto-loader will search recursively for class files `[RECURSIVE_SOURCING]`.

These options can be configured by providing the constructor a bit-mask flag as the third parameter. Check the constants section further down in this document for full details on what the flags do.
```php
// Here, we create an auto-loader with RECURSIVE_SOURCING disabled.
new AutoLoader(null, null, AutoLoader::DEFAULT_FLAGS & ~AutoLoader::RECURSIVE_SOURCING);
```
##### Adding Source Locations
To provide custom locations to source class files from, pass an array of paths to the constructor as the first parameter. Some things 
to note about the paths you provide:
- Directory separators do not need to match the environment, the auto-loader will fix them automatically.
- Paths will be computed to absolute locations, meaning symlinks/relative paths will be resolved.
- Source directories that cannot be found will throw an `InvalidSourcePathException`.
- Trailing slashes can be included or omitted, no issues will occur either way.
```php
new AutoLoader(["directory/relative/to/working/directory"]);
```

##### Extensions
By default, the auto-loader will only include files with the `.php` extension. To alter this behavior, provide an array containing 
the extensions you wish to load as the second parameter. Some things to note on extensions provided:
- Extensions provided will overwrite the existing default, so be sure to include `php` if you wish to retain that.
- The leading `.` is not required, however can still be included without breaking things.
```php
// Let's include .dat files... for some reason.
new AutoLoader(null, ["php", ".dat"]);
```

##### Namespaces
If your namespaces map directly to your directories, then auto-loading will work fine without additional configuration. For example, if
you have a class `MyNameSpace\TestClass` with the class file located at `files/MyNameSpace/TestClass.php`, then simply adding `files` as
a source will work.
```php
new AutoLoader(["files"]);
$test = new \MyNameSpace\TestClass();
```
On the other hand, you might have a namespace that does not correlate directly with the directory structure. For example, if you've 
used Git to include a third-party library to your project as a sub-module, you might find the namespace structure is 
`SnazzyLibrary\Math` but the class file is located in `SnazzyLibrary/src/Math.php`. To counter this, the auto-loader can 
map namespaces to directories, as follows.
```php
new AutoLoader(["SnazzyLibrary" => "SnazzyLibrary/src"]);
$math = new \SnazzyLibrary\Math();
```
___
### Constants
Constants available in the `AutoLoader` class:

constant | value | description
--- | --- | ---
`RECURSIVE_SOURCING` | `0x1` | Search recursively in added source locations for class files.
`INCLUDE_WORKING_DIRECTORY` | `0x2` | Working directory of the script will be added to the source list.
`INCLUDE_KRAMEWORK_DIRECTORY` | `0x4` | KrameWork directory will be added to the source list (same directory as AutoLoader.php)
`DEFAULT_FLAGS` | `*` | Alias flag which includes all of the above flags enabled.
___
### Functions
##### > __construct() : `void`
AutoLoader constructor.

parameter | type | description
--- | --- | ---
`$sources` | `array` | List of sources (strings) or namespace/source key-value array.
`$extensions` | `string[]` | Allowed extensions.
`$flags` | `int` | Flags to control auto-loading.

exception | reason
--- | ---
`InvalidSourcePathException` | Path could not be resolved.
##### > addSources() : `void`
Add sources to this auto-loader.

parameter | type | description
--- | --- | ---
`$sources` | `array` | List of sources (strings) or namespace/source key-value array.

exception | reason
--- | ---
`InvalidSourcePathException` | Path could not be resolved.
##### > loadClass() : `void`
Attempt to load a given class.

parameter | type | description
--- | --- | ---
`$className` | `string` | Name of the class to load.
##### > disable() : `void`
Disable this auto-loader, preventing it from loading classes.
##### > enable() : `void`
Enable this auto-loader, allowing it to load classes.
