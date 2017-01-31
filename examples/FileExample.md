## File
>- **Namespace**: KrameWork\Storage\File
>- **File**: KrameWork7/src/Storage/File.php

### Reading
The `File` class is a simple wrapper for, as you might be able to guess, files. The `__construct` method to create a new `File` instance takes three parameters:

 - `path` - **[Required]** Location of the file (even if it does not exist yet).
 - `autoLoad` - If true, the file will be read during initiation. Can throw `FileNotFoundException` or `FileReadException` **Default = true**
 - `touch` - If true, the file will be touched upon initiation of the instance. **Default = false**

With this, we can load the contents of a file rather simply.
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
### Writing/Saving
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
### Deleting
Deleting a file is as simple as telling it to be deleted! No error will be thrown if the file does not exist; you can check before-hand with `exists()` or `isValid()` if needed.
```php
$file = new File("someFile.txt", false);
$file->delete();
```