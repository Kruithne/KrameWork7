## KrameWork\Timing\Timer

***Table of Contents***
* **Overview** - Information about the class.
* **Examples** - Usage examples.
* **Constants** - Overview of constants exposed by the class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Overview
The timer class is very straight-forward and allows basic timing functionality, as you've probably already guessed! There are two timing formats currently available: seconds, used by default, and microseconds; you can control which is used by providing one of the constants exposed in the class to the constructor.
___
### Examples
Below are some basic examples of how to use the timer class.
```php
$timer = new Timer(Timer::FORMAT_SECONDS); // Use seconds, which is default anyway.
$timer->start(); // Manually start the timer.

sleep(5); // Let's wait for five seconds.

var_dump($timer->getElapsed()); // Prints 5~
var_dump($timer->stop()); // Prints 5~ and stops the timer!
```
As shown above, calls to `getElapsed()` do not stop the timer, a call to `stop()` however, will. Rather than calling `stop()` followed by `start()` to restart the timer, we can simply make a single call to `restart()`, which will also return the elapsed time before restarting the timer.
```php
// We use microseconds this time, and auto-start in the constructor!
$timer = new Timer(Timer::FORMAT_MICROSECONDS, true);

sleep(10); // Snooze for 10 seconds.
var_dump($timer->restart()); // Prints 10.000571966171~ and restarts.

sleep(10); // Snooze for another 10 seconds.
var_dump($timer->stop()); // Also prints 10.000571966171~, and stops!
```
___
### Constants
The constants provided with the `Timer` class can be provided to the constructor to control the timing format used by the `Timer` instance.

constant | value | description
--- | --- | ---
`FORMAT_SECONDS` | `0x1` | Format the timer in seconds.
`FORMAT_MICROSECONDS` | `0x2` | Format the timer in microseconds.
___
### Functions
##### > __construct() : `void`
Timer constructor.

parameter | type | description
--- | --- | ---
`$format` | `int` | Timing format, use Timer::FORMAT_ constants.
`$autoStart` | `bool` | Timer will start when constructed.
##### > start() : `void`
Start this timer.
##### > stop() : `void`
Stop the timer and return the current elapsed time.
##### > restart() : `void`
Restart the timer and return the current elapsed time.
##### > getElapsed() : `float|int`
Get the elapsed time of this timer.
##### > getStartTimestamp() : `float|int`
Returns the timestamp of when this timer started. If the timer is not started, will return 0.
##### > format() : `string`
Get the formatted result of this timer.

parameter | type | description
--- | --- | ---
`$format` | `string` | Format string.
##### > __toString() : `string`
Return the elapsed time as a string.
