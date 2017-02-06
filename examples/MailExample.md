## Mail
>- **Namespace**: KrameWork\Mailing\Mail
>- **File**: KrameWork7/src/Mailing/Mail.php

The `Mail` class, also known as **Micheal**, is an instance-based mail sending object with a fluent API. Below is the bare-bones example of how to send an e-mail using this class.
```php
new Mail()->addRecipient('foo@bar.net')->setSender('no-reply@bar.net')->send();
// Blank e-mail, subject: "No Subject", to foo@bar.net from no-reply@bar.net
```
> **Note**: Failure to add at least one recipient will throw an `InvalidRecipientException` upon `send()`.
> **Note**: Failure to set the sender for a mail object will throw a `MissingSenderException` upon `send()`.
### Mail Recipients
#### Adding
As shown in the original example, adding a recipient can be done by calling `addRecipient()` which has two overloads, the most common of which takes three parameters as follows.

 - `email (string)` - RFC 822 compliant e-mail address.
 - `name (string)` - Recipient name (8-bit unless next parameter is true).
 - `encode (bool)` - Encode the recipient name. Recommended. **Default: true**.

> **Note**: Attempting to add an e-mail address that does not comply with RFC 822 will throw an `InvalidRecipientException`.

The second overload takes an `array` in-place of the `email` parameter, and will treat it as a key/value pair array in the `email => name` format. If you wish to provide multiple recipients but omit the names, simply set them to null.
```php
$mail = new Mail();
$mail->addRecipient([
	'foo@bar.net' => 'Foo Bar', // Named recipient.
	'bar@foo.net' => null, // Non-named recipient.
]);
// Sends to: Foo Bar <foo@bar.net>, bar@foo.net
```
If needed with the second overload, you can disable the base64 encoding of recipient names the same way you would with the first overload, using the third parameter (omit second as `null`).

#### Removing
To remove a recipient from the mail object, simply call `removeRecipient()` with the e-mail address of the recipient.
```php
$mail = new Mail();
$mail->addRecipient('foo@bar.net', 'Foo Bar');
$mail->addRecipient('bar@foo.net');
$mail->removeRecipient('foo@bar.net');
// Sends to: bar@foo.net
```
In the situation that you want to remove all recipients from the mail object, a call to `clearRecipients()` would be simpler and cleaner; it does exactly what it says on the tin!
## Body
The body of the e-mail is the most important part; we can set the contents of it using `setBody()`.
```php
$mail = new Mail();
$mail->setBody('Hey Felicia!');
```
By default, the contents of the body will be sent as `text/plain`. If you plan on using HTML in the e-mail, you can specify this with the `containsHTML` parameter in the class constructor, which will send the body as `text/html` instead.
```php
$mail = new Mail(true); // Specify HTML.
$mail->setBody('<h1>Important E-mail Regarding Gummie Bears</h1>');
```
## Custom Headers
For various reasons, you may find yourself wanting to add custom headers to the mail object. This can be done with call to `addHeader(name, value)`, with both `name` and `value` being strings.
```php
$mail = new Mail();
$mail->addHeader('Reply-To', 'foo@bar.net');
```
> **Note**: By default, `MIME-Version` is set to `1.0`. It's not recommended to change this unless you know what you're doing as internals of the `Mail` object rely on it.
> **Note**: When calling `send()`, the headers `Content-Type`, `Content-Transfer-Encoding` and `Content-Disposition` will be over-written by the mailer depending on content type/attachments.
## Attachments
### Adding
Attachments can be added to a mail object by simply calling `attachFile(file)`, where `file` is either a path to a file (string) or an instance of the `File` class provided by KW7; some examples of these different cases can be seen below.
```php
// Example: Using a file path (string).
$mail = new Mail();
$mail->attachFile('attachments/duckPic.jpg');
// > Attaches 'attachments/duckPic.jpg' as 'duckPic.jpg'.
```
```php
$mail = new Mail();
$file = new File('attachments/horseDuck.jpg');
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
To remove an attachment, simply call `removeFile(file)`, where `file` is either the name of the file, the full path, or a `File` object.
```php
$mail = new Mail();
$mail->attachFile('attachments/ducks.jpg')->attachFile('attachments/horses.jpg');
$mail->removeFile('attachments/horses.jpg'); // Valid
$mail->removeFile('horses.jpg'); // Also valid.
// > 'attachments/ducks.jpg' will be the only attachment left.
```
If you wish to remove *all* attachments on the mail object, a call to `clearFiles()` would be simpler and cleaner!

### Sending
When everything is configured as you like, make a call to `send()` and, providing your server/environment mailing is set up correctly, your e-mail will be flying off through the internets for honor and glory.

E-mails are sent as `multipart/mixed` messages, encoded in `base64`.
