## KrameWork\Database

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.

___
### Overview
Work in progress, everything is subject to change.
___
### Examples
```php
$db = new DatabaseConnection('dblib:version=7.0;charset=UTF-8;host=mssqlsrvr;dbname=database');
$db->users = new UserSchema();
$db->updateSchema();
$user = $db->users->where(['username' => 'tallan'])->select();
$some = $db->users->where(['active' => 1])->limit(100)->select();
$db->users->drop();
$db->users->create();
$db->users->insert(['username' => 'ealbert']);
$id = $db->users->lastAutoId();
$data = $db->query('SELECT ROW_NUMBER() OVER (ORDER BY username) AS n, username FROM users');
```
