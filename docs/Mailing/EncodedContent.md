## KrameWork\Mailing\EncodedContent

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Constants** - Available constants exposed by this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `EncodedContent` class is a basic data container for mail content which allows automatic encoding based on the parameters you set. An instance of `Mail` uses the `EncodedContent` class for it's publicly exposed properties `plainContent` and `htmlContent`.
___
### Examples
Usage example of `EncodedContent` as exposed by a `Mail` instance.
```php
$mail = new Mail();
$mail->plainContent->setEncoding(EncodedContent::E_BASE64);
$mail->plainContent->setContent('This string will be encoded in base64 when sent via mail!');
```
If for some reason you desired to, you could provide your own instance of `EncodedContent`, however all settings applied by the `Mail` instance and your code will be lost to the aether, this is not recommended.
```php
$mail = new Mail();
$mail->plainContent = new EncodedContent(EncodedContent::QUOTED_PRINTABLE);
$mail->plainContent->setContent('This is odd.');
```
___
### Constants
The constants provided with the `EncodedContent` class:
constant | value | description
--- | --- | ---
`E_BASE64` | `base64` | Content will be encoded using base64.
`E_QUOTED_PRINTABLE` | `quoted-printable` | Content will be encoded as quoted printable.
`E_7BIT` | `7bit` | Content will use 7bit encoding.
___
### Functions
##### > __construct() : `void`
EncodedContent constructor.

parameter | type | description
--- | --- | ---
`$encoding` | `string` | Content encoding.
##### > setContent() : `void`
Set the content of this container.

parameter | type | description
--- | --- | ---
`$content` | `string` | Content data.
##### > hasContent() : `bool`
Check if this container has content.
##### > setEncoding() : `void`
Set the encoding of this content.

parameter | type | description
--- | --- | ---
`$encoding` | `string` | Encoding to use for this content.
##### > getEncoding() : `string`
Get the encoding of this content.
##### > __toString() : `string`
Encode and return the content.
