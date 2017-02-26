## KrameWork\Security\CSP

***Table of Contents***
* **Overview** - Information about the class.
* **Example** - Example usage of the class.
* **Constants** - Constants exposed by this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
`CSP` is a class used to generate a Content Security Policy for use as a HTTP header.
___
### Example
Below is an example use-case of this class, which will apply the following rules.
* Scripts may only originate from cdnjs.cloudflare.com and google.com, no inline embedding allowed.
* Fonts may only originate from fonts.googleapis.com
* Stylesheets may originate from cdnjs.cloudflare.com or be embedded inline.
* Multimedia objects and HTTP children are disabled.
* All other directives may only originate from the site domain under the HTTPS protocol.
```php
$csp = new CSP();
$csp->add(CSP::DIRECTIVE_DEFAULT, [CSP::SOURCE_HTTPS, CSP::SOURCE_SELF]);
$csp->add(CSP::DIRECTIVE_SCRIPT, ['https://cdnjs.cloudflare.com', 'https://www.google.com/']);
$csp->add(CSP::DIRECTIVE_FONT, ['https://fonts.googleapis.com/']);
$csp->add(CSP::DIRECTIVE_STYLE, [CSP::SOURCE_INLINE, 'https://cdnjs.cloudflare.com/']);
$csp->add([CSP::DIRECTIVE_OBJECT, CSP::DIRECTIVE_CHILD], CSP::SOURCE_NONE);
$csp->apply();

// Upon apply(), the following header is set.
// > Content-Security-Policy: default-src https: 'self'; script-src https://cdnjs.cloudflare.com https://www.google.com/; font-src https://fonts.googleapis.com/; style-src 'unsafe-inline' https://cdnjs.cloudflare.com/; object-src 'none'; child-src 'none'
```
___
### Constants
Below is a table containing all constants exposed by the `CSP` class.

constant | value | description
--- | --- | ---
`SOURCE_NONE` | 'none' | Nothing allowed.
`SOURCE_SELF` | 'self' | Same-domain only.
`SOURCE_INLINE` | 'unsafe-inline' | Inline allowed.
`SOURCE_EVAL` | 'unsafe-eval' | Inline allowed (with eval).
`SOURCE_HTTPS` | https: | Apply rule to HTTPS protocol.
`SOURCE_DATA` | data: | Apply rule to DATA protocol.
`DIRECTIVE_DEFAULT` | default-src | Fallback.
`DIRECTIVE_BASE` | base-uri | <base> URI.
`DIRECTIVE_CHILD` | child-src | Workers/embedded frames.
`DIRECTIVE_CONNECT` | connect-src | XHR, WebSockets, EventSource.
`DIRECTIVE_FONT` | font-src | Fonts
`DIRECTIVE_FORM` | form-action | Valid endpoints for forms.
`DIRECTIVE_ANCESTORS` | frame-ancestors | frame, iframe, embed, applet
`DIRECTIVE_IMAGE` | img-src | Images
`DIRECTIVE_MEDIA` | media-src | Video/audio.
`DIRECTIVE_OBJECT` | object-src | Flash/multimedia plug-ins.
`DIRECTIVE_PLUGIN` | plugin-types | Invokable plug-in types.
`DIRECTIVE_REPORT` | report-uri | URL for browser to report to.
`DIRECTIVE_STYLE` | style-src | Stylesheets.
`DIRECTIVE_SCRIPT` | script-src | Scripts.
`DIRECTIVE_UPGRADE` | upgrade-insecure-requests | Upgrade HTTP to HTTPS
___
### Functions
##### > __construct() : `void`
CSP constructor.

##### > add() : `void`
Add a directive to this policy.

parameter | type | description
--- | --- | ---
`$directives` | `array|string` | Directive (use CSP:: constants).
`$source` | `array|string` | Source directive.

##### > apply() : `void`
Set this policy as the Content-Security-Policy header.