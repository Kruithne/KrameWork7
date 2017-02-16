## KrameWork\MVC\Template

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`Template` is a basic class that plays a key role in the MVC structure of a site. Unlike in KW5, the `Module` classes are not provided in KW7; take a look below at how this would be employed in a very basic manner.
___
### Example
This is a basic use-case example of the `Template` class; actual implementations would likely be more advanced.
```php
// index.php
echo new MyPage('Lucy');

// template.php
<b>Hey <?php echo $this->user; ?>, I remember your name.</b>

// MyPage.php
class MyPage {
    public function __construct($name) {
        $this->template = new Template('template.php');
        $this->template->user = $name;
    }
    
    public function __toString() {
        return $this->template;
    }
    
    private $template;
}

// Output
<b>Hey Lucy, I remember your name.</b>
```
___
### Functions
##### > __construct() : `void`
Template constructor.

parameter | type | description
--- | --- | ---
`$file` | `string` | Path to a template file.

exception | reason
--- | ---
`InvalidTemplateException` | Template file is either missing or invalid.

##### > __get() : `mixed|null`
Obtain a value stored by this template. Returns null if the key does not exist in the template.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to get the value for.

##### > __set() : `void`
Set a value to be stored by this template.

parameter | type | description
--- | --- | ---
`$key` | `string` | Key to store the value with.
`$value` | `mixed` | Value to store.

##### > __toString() : `string`
Render this template and return it as a string.