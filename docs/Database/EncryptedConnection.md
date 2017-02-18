## KrameWork\Database\EncryptedConnection

***Table of Contents***
* **Overview** - Information about the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
This class provides the ability to hide connection strings in plain text, by using a static key to encrypt and decrypt a connection string.
___
### Functions
##### > encrypt() : `string An encrypted connection string`
Encrypt a connection string

parameter | type | description
--- | --- | ---
`string` | `$dsn` | A connection string
`int` | `$type` | Encryption format

