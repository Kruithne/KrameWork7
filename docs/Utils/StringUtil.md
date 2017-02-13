## KrameWork\Utils\StringUtil

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `StringUtil` class provides static methods for common string manipulation tasks that are used by some other components provided by KW7 but benefit from being contained in their own transportable class.
___
### Functions
##### > startsWith() : `bool`
Check if a string starts with another string.

parameter | type | description
--- | --- | ---
`$haystack` | `string` | String to search.
`$needle` | `string` | What the string should start with.
##### > endsWith() : `bool`
Check if a string ends with another string. Adapted from http://stackoverflow.com/a/834355/6997644

parameter | type | description
--- | --- | ---
`$haystack` | `string` | String to search.
`$needle` | `string` | What the string should end with.
##### > formatDirectorySlashes() : `string`
Convert all slashes in a string to match the environment directory separator.

parameter | type | description
--- | --- | ---
`$path` | `string` | Path to clean.
`$trimTrail` | `bool` | If true, trailing spaces/slashes will be trimmed.
##### > namespaceBase() : `string`
Get the base class-name from a namespace string.

parameter | type | description
--- | --- | ---
`$namespace` | `string` | Namespace path.