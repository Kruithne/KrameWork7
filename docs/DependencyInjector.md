## KrameWork\DependencyInjector

***Table of Contents***
* **Examples** - Usage examples.
* **Constants** - Constants exposed by this class.
* **Functions** - Comprehensive list of all functions in the class.

___
### Examples
##### General Usage
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
##### Manual Binding
In some cases, you may want to bind one class (or interface) name to another. We can achieve that with a simple call to `bind()`.
```php
$injector->bind("NotARealClassName", "MyClass");
$component = $injector->getComponent("NotARealClassName"); // Returns instance of MyClass.
```
##### Constructor
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
___
### Constants
Constants available in the `DependencyInjector` class:

constant | value | description
--- | --- | ---
`AUTO_BIND_INTERFACES` | `0x1` | Automatically bind the interfaces of added class objects.
`AUTO_ADD_DEPENDENCIES` | `0x2` | Dependencies for components will be automatically added to the injector.
`DEFAULT_FLAGS` | `*` | Alias flag with both flags above enabled.
___
### Functions
##### > __construct() : `void`
DependencyInjector constructor.

parameter | type | description
--- | --- | ---
`$flags` | `int` | Flags to control how this module behaves.
`$components` | `array` | Initial components.
`$bindings` | `array` | Initial bindings.
##### > addComponent() : `void`
Add a component to the injector. string: Name of a class which can be instantiated. object: Pre-constructed object. array: Multiple of the above.

parameter | type | description
--- | --- | ---
`$class` | `string|array|object` | 

exception | reason
--- | ---
`DuplicateClassException` | Class with that name has already been added.
##### > getComponent() : `object`
Obtain the injectors instance of a specific component. Non-constructed object instances will be instantiated.

parameter | type | description
--- | --- | ---
`$className` | `string` | Class name of the component to create.
`$add` | `bool` | Attempt to add the class to the injector if missing.

exception | reason
--- | ---
`ClassResolutionException` | Requested class could not be resolved.
`ClassInstantiationException` | ProvidedClass could not be instantiated (abstract? borked?)
##### > getImplementors() : `array`
Retrieve components from the injector that implement the given interface.

parameter | type | description
--- | --- | ---
`$interfaceName` | `string` | Interface components must implement to be returned.
`$add` | `bool` | Attempt to add the class to the injector if missing.
##### > bind() : `void`
Manually bind an interface to a class. $class string: Binds to class name. $class object: Binds to the objects class name.

parameter | type | description
--- | --- | ---
`$interface` | `string` | Interface name to bind class to.
`$class` | `string|object` | Class to bind the interface to.

exception | reason
--- | ---
`InterfaceBindingException` | `$class` was something invalid and unexpected.
