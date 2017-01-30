## JSONFile
>- **Namespace**: KrameWork\Storage\JSONFile
>- **File**: KrameWork7/src/Storage/JSONFile.php

The `JSONFile` class extends the `File` class and contains an internal `KeyValueContainer` by default.
Understanding of these classes may help understand this class, although it's simple by nature!

Load a JSON encoded file:
```php
$json = new JSONFile("bigData.json");
```
Access JSON values:
```php
print($json->myValue);
```
Assign values:
```php
$json->myValue = 42;
```
Save back to the file as encoded JSON:
```php
$json->save();
```
Create a wrapper, set options, then read:
```php
$json = new JSONFile("bigData.json", true, false);
$json->setRecursionDepth(1024);
$json->setOptions(JSON_BIGINT_AS_STRING);
$json->read();
```
Disable the internal container, and get the data raw.
```php
$json = new JSONFile("bigData.json", false);
$data = $json->getRawData(); // Raw decoded JSON, non-contained.
```
