## KeyValueContainer
>- **Namespace**: KrameWork\Storage\KeyValueContainer
>- **File**: KrameWork7/src/Storage/KeyValueContainer.php

A `KeyValueContainer` is a simple class that contains assigned data by a given key and can be serialized in various
ways.

Create a new container:
```php
$container = new KeyValueContainer();
```
Assign a variable to the container:
```php
$container->thing = 42;
```
Access a variable inside the container:
```php
print($container->thing); // 42
```
Serialize the container to a string:
```php
$serialized = $container->serialize();
```
Load data from a serialized string (overwrites existing data in the container).
```php
$container->unserialize($serialized);
```
Access the internal data array:
```php
print_r($container->asArray());
```
The containers implement `JsonSerializable`. (Check JSONFile for better JSON handling)
```php
$json = json_encode($container);
```
