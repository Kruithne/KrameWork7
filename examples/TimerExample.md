## Timer
>- **Namespace**: KrameWork\Timing\Timer
>- **File**: KrameWork7/src/Timing/Timer.php

### Usage
The timer class is very straight-forward and allows basic timing functionality, as you've probably already guessed! There are two formats currently available: `FORMAT_SECONDS` and `FORMAT_MICROSECONDS`, which can be specified to the constructor.
```php
$timer = new Timer(Timer::FORMAT_SECONDS);
$timer->start();

sleep(5);

var_dump($timer->getElapsed()); // Prints 5~.
```
Calls to `getElapsed()` will not stop the timer, if you desire to do that, you can call `stop()`, which will both stop the timer and also return the elapsed time.
```php
$timer = new Timer(Timer::FORMAT_SECONDS);
$timer->start();
sprintf("Timer stopped after %s seconds", $timer->stop());
```
Rather than calling `start()` directly after the constructor every time, the second parameter of the constructor, `autoStart`, allows us to start the timer automatically.
```php
$timer = new Timer(Timer::FORMAT_SECONDS, true);
```
If a timer is already running, and we want to reset it, we could call `start()` again, but this won't return us with the elapsed time. Rather than calling a combination of functions to achieve this, we can simply call `restart()` which returns the elapsed time and restarts the timer, all in one call.

### Extra
The `Timer` class implements `__toString()`, allowing it to be passed directly to functions that expect a string. The result will be the elapsed time (`getElapsed()`) cast to a string.