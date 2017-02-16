[fluent api]: https://en.wikipedia.org/wiki/Fluent_interface
## KrameWork\Utils\StringBuilder

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`StringBuilder` is a [fluent API] class that allows object-orientated string concatenation and manipulation. Elements can be appended and prepended in various ways using the functions provided to seamlessly create a string using a combination of different types and methods.

Internally, the `StringBuilder` class stores elements by-line in an array stack to speed up processing of various helper functions; this has no effect on the compiled outcome once `__toString()` is invoked.
___
##### Inserting Elements
Elements can be inserted to a builder instance through various methods, some of which are exampled below. All elements must be a native type or implement `__toString()`. If an element is an array, the array will be recursively iterated and all elements appended; some functions, such as `appendf`, do not accept arrays - check the function reference at the bottom of the page.
```php
// Anything passed to the constructor is appended.
$builder = new StringBuilder('Agent', 47); // > Agent47

// The append() call can take multiple arguments, and we can chain calls using fluent API.
$builder->append(' look ', 'like')->append('?'); // > Agent47 look like?

// Prepending works the same, but adds elements to the start of the string.
// Note: Items are prepended one after the other, check the input compared to output below.
$builder->prepend(' does ', 'What'); // > What does Agent47 look like?

// Insert formatted strings!
$builder->appendf(' Very %s!', 'scary'); // > What does Agent47 look like? Very scary!

// Insert an element, followed by a line-end.
// By default, line-endings will be Unix style (\n), however this can be changed using
// the setLineEnd() call; check the function reference for more details.
$builder->appendLine(' Very Strong!'); // > What does Agent47 look like? Very scary! Very strong!\n
```
___
### Functions
##### > __construct() : `void`
StringBuilder constructor.

parameter | type | description
--- | --- | ---
`$args` | `array` | Initial elements to append to the builder.
##### > append() : `StringBuilder`
Append one or more elements to the builder. Arrays will be recursively iterated with all elements appended.

parameter | type | description
--- | --- | ---
`$args` | `array` | Elements to append to the builder.
##### > appendLine() : `StringBuilder`
Append elements to the builder with a line-end prefix/suffix. Defaults to Unix line-end unless specified using setLineEnd(). Providing a null element is equivalent to calling newLine(true). Note: One line-end added per function call, not per element.

parameter | type | description
--- | --- | ---
`$line` | `string|array|null` | Element(s) to append.
`$suffix` | `bool` | Line-end will be suffix, otherwise prefix.
##### > appendf() : `StringBuilder`
Append a single formatted string to the builder.

parameter | type | description
--- | --- | ---
`$format` | `string` | String format pattern.
`$args` | `array` | Components for the format pattern.
##### > prepend() : `StringBuilder`
Prepend one or more element to the builder. Arrays will be recursively iterated with all elements prepended.

parameter | type | description
--- | --- | ---
`$args` | `array` | Elements to prepend to the builder.
##### > prependLine() : `StringBuilder`
Prepend elements to the builder with a line-end prefix/suffix. Defaults to Unix line-end unless specified using setLineEnd(). Providing a null element is equivalent to calling newLine(false). Note: One line-end added per function call, not per element.

parameter | type | description
--- | --- | ---
`$line` | `string|array|null` | Element to prepend.
`$suffix` | `bool` | Line-end will be suffix, otherwise prefix.
##### > prependf() : `StringBuilder`
Prepend a single formatted string to the builder.

parameter | type | description
--- | --- | ---
`$format` | `string` | String format pattern.
`$args` | `array` | Components for the format pattern.
##### > repeat() : `StringBuilder`
Add an element $count amount of times to the builder. Arrays will be recursively iterated with each element added.

parameter | type | description
--- | --- | ---
`$input` | `string|array` | Element to repeat.
`$count` | `int` | How many times to append/prepend the element.
`$append` | `bool` | Append the element, otherwise prepend.
##### > newLine() : `StringBuilder`
Add a single line-end to the builder. Defaults to Unix line-end unless specified using setLineEnd().

parameter | type | description
--- | --- | ---
`$append` | `bool` | Append the line-end, otherwise prepend.
##### > clear() : `StringBuilder`
Clear the builder, resetting it completely and deleting all elements that have been added.
##### > length() : `int`
Retrieve the total length of content contained in the builder.
##### > isEmpty() : `bool`
Check if the builder is empty.
##### > setSeparator() : `StringBuilder`
Set the separator for the StringBuilder. Not retroactive; only effects newly appended content. To disable, supply a null value.

parameter | type | description
--- | --- | ---
`$sep` | `string|null` | Separator character.
##### > getLineEnd() : `string`
Get the line-end character used by this string builder.
##### > setLineEnd() : `StringBuilder`
Set the line-end character to use in this builder.

parameter | type | description
--- | --- | ---
`$lineEnd` | `string` | Line-end; check StringBuilder::LE_* constants.
##### > __toString() : `string`
Return the compiled result of the string builder.
##### > indent() : `StringBuilder`
Increase the indentation level for future input.

parameter | type | description
--- | --- | ---
`$value` | `int` | How many levels to increase indentation by.
##### > outdent() : `StringBuilder`
Decrease the indentation level for future input.

parameter | type | description
--- | --- | ---
`$value` | `int` | How many levels to decrease indentation by.
##### > appendArray() : `StringBuilder`
Append an array to the string builder with a given separator.

parameter | type | description
--- | --- | ---
`$arr` | `array` | Array to append.
`$sep` | `string` | Separator to use between the elements.
##### > prependArray() : `StringBuilder`
Prepend an array to the string builder with a given separator.

parameter | type | description
--- | --- | ---
`$arr` | `array` | Array to prepend.
`$sep` | `string` | Separator to use between the elements.