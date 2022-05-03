## KrameWork\HTTP\Cookies
### Defaults
Below are the default values used by KrameWork when setting cookies. These can be overwritten using either `Cookies::setDefault()` or `Cookies::setDefaults()`.

```
'expires' => 0,
'domain' => '',
'path' => '/',
'secure' => true,
'httponly' => true,
'samesite' => 'None'
```

___
### Functions

##### > Cookies::set() : `void`
Set the value of a cookie. 

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the cookie to set.
`$value` | `string` | Value of the cookie.
`$options` | `array` | Optional array of cookie parameters.

##### > Cookies::get() : `array|string|null`
Retrieve the value of a given cookie. Returns NULL if the cookie is not set.

##### > Cookies::expire() : `void`
Expires a cookie with the given name. When called, the content of the cookie is set to a blank string and the expiration time is set to `1` (one second after epoch, which is in the past).

parameter | type | description
--- | --- | ---
`$name` | `string` | Name of the cookie to expire.

##### > Cookies::setDefault() : `void`
Override the value of a specific default option. Existing defaults are at the top of this document.

parameter | type | description
--- | --- | ---
`$key` | `string` | Default option to override.
`$value` | `mixed` | Value to set.

##### > Cookies::setDefaults() : `void`
Override the value of multiple default options. Existing defaults are at the top of the this document.

If `$merge` is set to `false`, the provided array entirely replaces the default options, otherwise the two are merged with the provided array taking precedence.

parameter | type | description
--- | --- | ---
`$options` | `array` | Default options to override.
`$merge` | `boolean` | Merge provided defaults.