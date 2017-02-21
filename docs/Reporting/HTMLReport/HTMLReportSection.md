## KrameWork\Reporting\HTMLReport\HTMLReportSection

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Example usage.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The `HTMLReportSection` is a sub-class for the `HTMLReportTemplate` class, and is vital in enabling multi-instance section replacements.
___
### Example
The example below shows how to substitute a section multiple times (multi-instance replacement).
```php
// myErrorReport.html
<div>
    <p><!--MESSAGE--></p>
    <!--NUMBERS-->
        <b><!--INDEX--></b>
    <!--/NUMBERS-->
</div>

// report.php
$contents = file_get_contents('myErrorReport.html');
$report = new HTMLReportTemplate($contents);
$report->message = 'Hello, world!';

$section = $report->getSection('NUMBERS');
for ($i = 1; $i < 5; $i++) {
	$frame = $section->createFrame();
	$frame->index = $i;
}

echo $report;

// Result:
<div>
    <p>Hello, world!</p>
    <b>1</b>
    <b>2</b>
    <b>3</b>
    <b>4</b>
</div>
```
___
### Functions
##### > __construct() : `void`
HTMLReportSection constructor.

parameter | type | description
--- | --- | ---
`$tag` | `string` | Section tag.
`$content` | `string` | Content containing the section.

##### > isValid() : `bool`
Check if this section is valid (exists in the template).

##### > getSectionStart() : `int`
Get the start index of this section within the template.

##### > getSectionLength() : `int`
Get the length of this section within the template.

##### > createFrame() : `HTMLReportTemplate`
Creates a new frame for this section. Reference to the returned template is retained by the section for compilation.

##### > validate() : `bool`
Validate the content/position of this section in the given chunk.

parameter | type | description
--- | --- | ---
`$content` | `string` | Content that contains the section.