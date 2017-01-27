## AutoLoader
>- **Namespace**: KrameWork\AutoLoader
>- **File**: KrameWork7/src/AutoLoader.php

### Basic Usage
Setting up the auto-loader is as simple as creating a new instance of it.
```php
new AutoLoader();
```
Initiating the auto-loader without any parameters, will adopt the following default behavior:
- [x] Files inside of the working directory will be sourced for auto-loading `[INCLUDE_WORKING_DIRECTORY]`.
- [x] Files inside of the KrameWork directory will be sourced for auto-loading `[INCLUDE_KRAMEWORK_DIRECTORY]`.
- [x] Auto-loader will search recursively for class files `[RECURSIVE_SOURCING]`.

These options can be configured by providing the constructor a bit-mask flag as the third parameter.
```php
// Here, we create an auto-loader with RECURSIVE_SOURCING disabled.
new AutoLoader(null, null, AutoLoader::DEFAULT_FLAGS & ~AutoLoader::RECURSIVE_SOURCING);
```
### Adding Source Locations
To provide custom locations to source class files from, pass an array of paths to the consturctor as the first parameter. Some things 
to note about the paths you provide:
- Directory seperators do not need to match the environment, the auto-loader will fix them automatically.
- Paths will be computed to absolute locations, meaning symlinks/relative paths will be resolved.
- Paths that cannot be found/access will be discarded without an error, and classes will not load from them.
- Trailing slashes can be included or omitted, no issues will occur either way.
```php
new AutoLoader(["directory/relative/to/working/directory"]);
```

### Extensions
By default, the auto-loader will only include files with the `.php` extension. To alter this behavior, provide an array containing 
the extensions you wish to load as the second parameter. Some things to note on extensions provided:
- Extensions provided will overwrite the existing default, so be sure to include `php` if you wish to retain that.
- The leading `.` is not required, however can still be included without breaking things.
```php
// Let's include .dat files... for some reason.
new AutoLoader(null, ["php", ".dat"]);
```

### Namespaces
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
