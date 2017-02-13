## JSONFile
>- **Namespace**: KrameWork\Storage\JSONFile
>- **File**: KrameWork7/src/Storage/JSONFile.php

The `JSONFile` class extends the `File` class and contains an internal `ArrayObject` by default. An understanding of both these classes will help when working with `JSONFile`.

### Reading
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

### Writing
Once you have a `JSONFile` object, you can assign values to it. If you want to persist those changes to the disk, call `save()`. Check the [FileExample](FileExample.md) document for more information and examples on file-saving.
```php
$json = new JSONFile("myData.json", true, false);
$json->myValue = true;
$json->save();
```
### Decoding Options
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