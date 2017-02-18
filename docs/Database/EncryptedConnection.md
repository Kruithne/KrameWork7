## KrameWork\Database\EncryptedConnection

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
This class provides the ability to hide connection strings in plain text, by using a static key to encrypt and decrypt a connection string.
___
### Examples
```php
$dsn = new EncryptedConnection('H1$....');
$database = new Database($dsn);
$data = $database->getAll('SELECT data FROM source');
```

The get the encrypted value `H1$...` you will need to run code like this in CLI:
```php
echo EncryptedConnection::encrypt('dblib:....');
```

___
### Functions
##### > encrypt() : `string An encrypted connection string`
Encrypt a connection string

parameter | type | description
--- | --- | ---
`string` | `$dsn` | A connection string
`int` | `$type` | Encryption format

