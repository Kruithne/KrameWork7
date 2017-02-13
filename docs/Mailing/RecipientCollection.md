[fluent API]: https://en.wikipedia.org/wiki/Fluent_interface
## KrameWork\Mailing\RecipientCollection

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `RecipientCollection` class is a [fluent API] data container for mail recipients which allows for easy adding/removing of recipients to `Mail` instances. An instance of `Mail` uses the `RecipientCollection` class for it's publicly exposed properties `to`, `cc` and `bcc`.
___
### Examples
Usage example of `RecipientCollection`, as exposed by a `Mail` instance.
```php
$mail = new Mail();
$mail->to->add('foo@bar.net'); // Add a simple address.
$mail->to->add('bar@foo.net', 'Foo Bar'); // Add a named recipient.
$mail->to->clear(); // Remove both recipients we just added.

// Repeat the same operation as above, but using an array.
$mail->to->add([
    'foo@bar.net' => null, // Unnamed
    'bar@foo.net' => 'Foo Bar', // Named
]);
$mail->to->remove('bar@foo.net'); // Remove just the second added address this time.
```
___
### Functions
##### > __construct() : `void`
RecipientCollection constructor.
##### > add() : `RecipientCollection`
Add a recipient (or multiple) to this collection. Arrays must be in an email=>name format (name can be null).

parameter | type | description
--- | --- | ---
`$email` | `string|array` | RFC 822 compliant e-mail address(es).
`$name` | `null|string` | Name of the e-mail recipient.
`$encode` | `bool` | Encode the e-mail recipient name.

exception | reason
--- | ---
`InvalidRecipientException` | E-mail address did not conform to RFC 822.
##### > remove() : `RecipientCollection`
Remove a recipient (or multiple) from this collection.

parameter | type | description
--- | --- | ---
`$email` | `string|array` | E-mail address(es) to remove.
##### > clear() : `RecipientCollection`
Clear all recipients from this collection.
##### > isEmpty() : `bool`
Check if this collection contains no recipients.
##### > __toString() : `string`
Compile the recipients into a comma-separated string.
