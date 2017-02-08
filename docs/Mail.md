## Mail
>- **Namespace**: KrameWork\Mailing\Mail
>- **File**: KrameWork7/src/Mailing/Mail.php

The `Mail` class, also known as **Micheal**, is an instance-based mail sending object with a fluent API. Below is the bare-bones example of how to send an e-mail using this class.
```php
$mail = new Mail();
$mail->to->add("foo@bar.net");
$mail->setSender("no-reply@bar.net");
// Blank e-mail, subject: "No Subject", to foo@bar.net from no-reply@bar.net
```
> **Note**: Failure to add at least one recipient will throw an `InvalidRecipientException` upon `send()`.
> **Note**: Failure to set the sender for a mail object will throw a `MissingSenderException` upon `send()`.
### Mail Recipients
#### Adding
As shown in the original example, adding a recipient can be done by calling `$mail->to->add()` which has two overloads, the most common of which takes three parameters as follows.

 - `email (string)` - RFC 822 compliant e-mail address.
 - `name (string)` - Recipient name (8-bit unless next parameter is true).
 - `encode (bool)` - Encode the recipient name. Recommended. **Default: true**.

> **Note**: Attempting to add an e-mail address that does not comply with RFC 822 will throw an `InvalidRecipientException`.

The second overload takes an `array` in-place of the `email` parameter, and will treat it as a key/value pair array in the `email => name` format. If you wish to provide multiple recipients but omit the names, simply set them to null.
```php
$mail = new Mail();
$mail->to->add([
	'foo@bar.net' => 'Foo Bar', // Named recipient.
	'bar@foo.net' => null, // Non-named recipient.
]);
// Sends to: Foo Bar <foo@bar.net>, bar@foo.net
```
If needed with the second overload, you can disable the base64 encoding of recipient names the same way you would with the first overload, using the third parameter (omit second as `null`).

#### Removing
To remove a recipient from the mail object, simply call `$mail->to->remove()` with the e-mail address of the recipient.
```php
$mail = new Mail();
$mail->to->add('foo@bar.net', 'Foo Bar');
$mail->to->add('bar@foo.net');
$mail->to->remove('foo@bar.net');
// Sends to: bar@foo.net
```
In the situation that you want to remove all recipients from the mail object, a call to `$mail->to->clear()` would be simpler and cleaner; it does exactly what it says on the tin!
### CC / BCC
Identical to the `to` property, the properties `bcc` and `cc` also exist, which can be used in the same way as adding normal recipients above.
```php
$mail = new Mail();
$mail->to->add('foo@bar.net'); // Primary Recipient.
$mail->cc->add('bar@foo.net'); // Cc
$mail->bcc->add('net@foo.bar'); // Bcc
```
## Body
There are two bodies for an e-mail, a plain-text version which can be controlled using the `plainContent` property, and the HTML version which can be controlled using the `htmlContent` property. It's highly recommended that you always provide a plain-text version, even when providing a HTML version, for clients that do not support HTML. While both versions will be sent, only the most relevant version will be rendered by the client.
```php
$mail = new Mail();
$mail->htmlContent->setContent('<i>Dear Foo.</i><br/><b>Please send help!</b>');
$mail->plainContent->setContent('Dear Foo. Please send help.');
```
By default, the encoding for the plain content will be `7bit` and the encoding for the HTML content will be `quoted-printable`. You can change the encoding at any point using the `setEncoding()` method on each content property.
```php
$mail->htmlContent->setEncoding('base64');
```
Content provided to the mail object will be automatically converted depending on the encoding you set, for example if you set the encoding to `base64` as shown in the example above, the content you set will be encoded to `base64` before being provided to the e-mail agent.
## Custom Headers
For various reasons, you may find yourself wanting to add custom headers to the mail object. This can be done with a call to `addHeader(name, value)`, with both `name` and `value` being strings.
```php
$mail = new Mail();
$mail->addHeader('Reply-To', 'foo@bar.net');
```
> **Note**: By default, `MIME-Version` is set to `1.0`. It's not recommended to change this unless you know what you're doing as internals of the `Mail` object rely on it.
> **Note**: When calling `send()`, the headers `Content-Type`, `Content-Transfer-Encoding` and `Content-Disposition` may be over-written by the mailer depending on content type/attachments.
## Attachments
### Adding
Attachments can be added to a mail object by simply calling `attachFile(file)`, where `file` is either a path to a file (string) or an instance of the `AttachmentFile`, an extension of the KW7 `File` class; some examples of these different cases can be seen below.
```php
// Example: Using a file path (string).
$mail = new Mail();
$mail->attachFile('attachments/duckPic.jpg');
// > Attaches 'attachments/duckPic.jpg' as 'duckPic.jpg'.
```
```php
$mail = new Mail();
$file = new AttachmentFile('attachments/horseDuck.jpg');
$mail->attachFile($file);
// > Attaches 'attachments/horseDuck.jpg' as 'horseDuck.jpg'.
```
```php
$mail = new Mail();
$file = new MemoryFile('secrets.txt', 'hunter10', 'text/plain');
$mail->attachFile($file);
// > Attaches text file containing 'hunter10' as 'secrets.txt'.
```
> **Note**: Attachments are indexed by their basename, thus two files with the same basename cannot be added. Attempting to do this will throw a `DuplicateAttachmentException`.
### Removing
To remove an attachment, simply call `removeFile(file)`, where `file` is either the name of the file, the full path, or a `AttachmentFile` object.
```php
$mail = new Mail();
$mail->attachFile('attachments/ducks.jpg')->attachFile('attachments/horses.jpg');
$mail->removeFile('attachments/horses.jpg'); // Valid
$mail->removeFile('horses.jpg'); // Also valid.
// > 'attachments/ducks.jpg' will be the only attachment left.
```
If you wish to remove *all* attachments on the mail object, a call to `clearFiles()` would be simpler and cleaner!
### Inline
By default, attachments are attached as attachments.. which sounds obvious, but there is another option! Attachments can also have their disposition set to `inline`, which means they are available for embedding within content. This can be achieved by providing `true` to the second parameter of `attachFile()`.
```php
$mail = new Mail();
$mail->attachFile('attachments/duck.jpg', true);
$mail->htmlContent->setContent('<img src="cid:duck.jpg"/>');
```
As shown in the example above, files are embedded with their base name as the content ID (CID) by default. If you wish to provide your own CID, you'll need to provide your own instance of `AttachmentFile` with the CID set using `setContentID(string id)`.
```php
$mail = new Mail();
$file = new AttachmentFile('attachments/duck.jpg', false);
$file->setContentID(uniqid('attach'));
$mail->attachFile($file, true);
```

### Sending
When everything is configured as you like, make a call to `send()` and, providing your server/environment mailing is set up correctly, your e-mail will be flying off through the internets for honor and glory.

E-mails are sent as `multipart/mixed` with a sub-boundary of `multipart/alternative` for the content, which can contain both plain-text and HTML versions of the same message based on your input. Mail subjects, attachments, recipient names (by default) and HTML content are encoded in `base64`, while everything else uses `7bit` encoding. The `UTF-8` charset is used for everything in the mail instance.
