## DependencyInjector
>- **Namespace**: KrameWork\DependencyInjector
>- **File**: KrameWork7/src/DependencyInjector.php

### General Usage
The `DependencyInjector` is as the name suggests, a dependency injector. Below is a basic usage example.

```php
$injector = new DependencyInjector();
$injector->addComponent("MyClass");

$component = $injector->getComponent("MyClass");
```
`$component` is now a constructed instance of `MyClass`. Calling `getComponent("MyClass")` again will return the same instance.
This usage isn't very helpful though, let's try another example where `MyClass` has dependencies.
```php
class MyClass {
	public function __construct(MyRequiredClass $class) {
		// Do stuff.
	}
}
```
Even without adding `MyRequiredClass` to the injector, providing the class is loaded (or will be loaded by an auto-loader), the 
injector will create a new instance of `MyRequiredClass` and provide it to `MyClass` when creating it. The instance of `MyRequiredClass` 
that gets created, will be kept and provided to other objects that require it.

The chain of dependencies can go as deep as you like, but beware of cyclic dependencies! The below version of `MyClass` will throw an 
exception because `MyClass` requires itself to be constructed before it's constructed, thus creating an infinite cycle.
```php
class MyClass {
	public function __construct(MyClass $class) {
		// Do stuff?
	}
}
```
Let's take a look at another example where `MyClass` implements an interface.
```php
class MyClass implements IMyInterface {
// ...
```
By default, the dependency injector will map classes with the interfaces they implement, meaning the following example will 
produce an instance of `MyClass` (providing it's the only component implementing the interface).
```php
$component = $injector->getComponent("IMyInterface");
```
When retrieving components that implement an interface, it's better to make use of the `getImplementors()` function as, unlike `getComponent()`, it can return multiple components at once.
```php
$classA = new class implements IMyInterface {};
$classB = new class implements IMyInterface {};

$injector->addComponent([$classA, $classB]);
$components = $injector->getImplementors("IMyInterface"); // Returns $classA and $classB in an array.
```
As you may have spotted in that last example, we called `addComponent()` with an array instead of a class name (string). The 
`addComponent()` call also accepts pre-constructed objects, which will be mapped to their own class name.
```php
$obj = new MyClass();
$injector->addComponent($obj);
$component = $injector->getComponent("MyClass"); // Returns $obj
```
### Manual Binding
In some cases, you may want to bind one class (or interface) name to another. We can achieve that with a simple call to `bind()`.
```php
$injector->bind("NotARealClassName", "MyClass");
$component = $injector->getComponent("NotARealClassName"); // Returns instance of MyClass.
```
### Constructor
For default behavior, the constructor of `DependencyInjector` can be empty, however we may want to customize the behavior of the 
injector. The constructor takes three arguments: `flags`, `components[]` and `bindings[]`.

`flags` is a bit-mask value that defines the behavior of the injector. Below are the possible options, which can be found as 
public constants on the `DependencyInjector` class. All of these options are enabled by default.
- AUTO_BIND_INTERFACES - When adding a component, it's class will be found to any interfaces it implements.
- AUTO_ADD_DEPENDENCIES - During component construction, missing dependencies will be added/constructed.

The `components[]` and `bindings[]` arguments are both arrays which allow you to provide components and bindings straight 
into the constructor without needing additional calls to do so.
```php
// This...
$injector = new DependencyInjector(null, ["MyClassA", "MyClassB"], ["INotReal" => "MyClassA"]);

// Is equivalent to this...
$injector = new DependencyInjector(null);
$injector->addComponent("MyClassA");
$injector->addComponent("MyClassB");
$injector->bind("INotReal", "MyClassA");
```
