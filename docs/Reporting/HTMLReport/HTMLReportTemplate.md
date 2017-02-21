## KrameWork\Reporting\HTMLReport\HTMLReportTemplate

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Example usage.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `HTMLReportTemplate` class allows dynamic construction of a report using a HTML template. This class heavily relies on its counterpart, `HTMLReportSection` for multi-instance section replacements.

___
### Example
The example below shows basic value substitution in a HTML template using this class. For multi-instance section replacements, check the `HTMLReportSection` documentation file.
```php
// myErrorReport.html
<div>
    <p><!--MESSAGE--></p>
</div>

// report.php
$contents = file_get_contents('myErrorReport.html');
$report = new HTMLReportTemplate($contents);
$report->message = 'Hello, world!';

echo $report;

// Result:
<div>
    <p>Hello, world!</p>
</div>
```

> **Note**: While tags are case-insensitive, whitespace is important. <!-- MESSAGE--> will not be matched since it contains whitespace before the MESSAGE tag name.
___
### Functions
##### > __construct() : `void`
HTMLReportTemplate constructor.

parameter | type | description
--- | --- | ---
`$content` | `string` | Content of the template.

##### > getSection() : `HTMLReportSection`
Get a section of this template. Returned section will be retained by this template for compilation.

parameter | type | description
--- | --- | ---
`$tag` | `string` | Enclosing tags for the section.

##### > __set() : `void`
Set a simple replacement for this template.

parameter | type | description
--- | --- | ---
`$tag` | `string` | Tag to search for.
`$value` | `mixed` | Replacement value.

##### > __toString() : `string`
Compile this template and return it as a string.

