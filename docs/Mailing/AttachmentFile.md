## KrameWork\Mailing\AttachmentFile : KrameWork\Storage\File

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `AttachmentFile` class is an extension of the `File` class provided in the `KrameWork\Storage` namespace, which allows all of the typical functionality of file control with some added benefits for mail attachment.
___
### Examples
Usage example of the `AttachmentFile` class:
```php
$file = new AttachmentFile('myFile.json');
$file->setInline(true); // Allow inline embedding of this attachment.
$file->setContentID('my-content-id'); // Give a unique CID (will use the file name if not provided).

$mail = new Mail();
$mail->attachFile($file); // Hoorah!
```
___
### Functions
##### > setInline() : `void`
Set if this attachment should be embedded inline.

parameter | type | description
--- | --- | ---
`$inline` | `bool` | Embed attachment inline.
##### > isInline() : `bool`
Check if this attachment should be inline.
##### > setContentID() : `void`
Set the content ID of this attachment.

parameter | type | description
--- | --- | ---
`$id` | `string` | CID for this attachment.
##### > getContentID() : `string`
Get the content ID of this attachment.
