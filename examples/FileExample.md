## File
>- **Namespace**: KrameWork\Storage\File
>- **File**: KrameWork7/src/Storage/File.php

The `File` class is a simple wrapper for files created mostly for testing the `BaseFile` class, but might be handy in 
saving you a couple of lines of code. Creating a file wrapper is simple, just construct the class!
```php
$file = new File();
```
Without any parameters, this will create an empty wrapper. But that's no fun, let's load some data into it! We didn't provide a 
path, but we can provide one with the `read()` call, as follows.
```php
$file->read("myFile.txt");
```
It's important to note that the `read()` function will throw a `KrameWorkFileException` in the following circumstances:
- No file was specified (either in the `read()` call or the wrapper constructor)
- The file cannot be found.
- The file cannot be accessed.
For these reasons, it's best to provide the path to the file through the constructor, so you can check if the wrapped file exists 
by calling `exists()`.
```php
$file = new File("myFile.txt");
if ($file->exists())
    $file->read(); // Since we provided the file name with the constructor, we don't need it here.
```
Now we've loaded the data from `myFile.txt` into the wrapper, how do we read it? A simple call to `getData()` will return the data  
loaded from the file. Calling `getData()` on an empty wrapper will simply return an empty string.
```php
$data = $file->getData();
```
As you may have guessed, you can set the data for the wrapper using the `setData($data)` call.
```php
$file->setData("Hello, file!");
```
This sets the data for the wrapper, but doesn't save it to the file yet. You can do that by calling `save()`. If you wish to save the 
data to another location, you can provide a path to the `save($path)` call.
```php
$file->save("somewhereElse.txt");
```
