## StringBuilder
>- **Namespace**: KrameWork\Utils\StringBuilder
>- **File**: KrameWork7/src/Utils\StringBuilder.php

### Basic Usage
The `StringBuilder` class, or as his friends call him, **Sam**, is a fluent API class that allows dynamic construction of strings with various helper functions.

The constructor accepts any number of objects that can be cast to a string either nativly or by `__toString()` which will be added to the stack upon instantiation.
```php
$builder = new StringBuilder("Agent ", 47);
print($builder); // > Agent 47
```
### Appending/Prepending
The `append()` function will add things to the stack in the same way that the constructor will, however in addition to accepting native types and objects that implement `__toString()`, it also accepts arrays which will be iterated and called per-item.
```php
$builder = new StringBuilder();
$builder->append("1", 2, [3, [4, 5], 6]);
print($builder); // > 123456
```
To add things to the front of the stack, we can use the `prepend()` call, which accepts a variable amount of arguments in the same fashion as `append()`.
```php
$builder = new StringBuilder();
$builder->append("1", 2, [3, [4, 5], 6]);
print($builder); // > 654321
```
### Resetting
Rather than creating a new instance of `StringBuilder` to start again, we can simply make a call to `clear()`, and the internal stack will be reset.
```php
$builder = new StringBuilder("Fish");
$builder->append("Crabs")->clear()->append("Shark");
print($builder); // > Shark
```
### Repeat
A 'handy' function available in the `StringBuilder` class is `repeat(input, int count, bool append)`, which allows us to append/prepend something a specific amount of times.

 - `input` - Native type or object that implements `__toString()`. Arrays not welcome here.
 - `count` - How many times the string will be added.
 - `append` - True = append, false = prepend.

```php
$builder = new StringBuilder("BATMAN!");
$builder->repeat("NA ", 14, false);
print($builder); // > NA NA NA NA NA NA NA NA NA NA NA NA NA NA BATMAN!
```