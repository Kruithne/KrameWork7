<?php
	namespace KrameWork\DI;

	class KrameWorkDependencyInjectorException extends \Exception {
		/**
		 * KrameWorkDependencyInjectorException constructor.
		 * @param string $message Message of the exception.
		 * @param int $className Name of the class involved in the exception.
		 */
		public function __construct($message, $className) {
			parent::__construct(sprintf($message, $className), 0, null);
		}
	}

	class DependencyInjector {
		const BIND_INTERFACES = 0x1;
		const AUTO_ADD_DEPENDENCIES = 0x2;
		const DEFAULT_FLAGS = self::BIND_INTERFACES | self::AUTO_ADD_DEPENDENCIES;

		/**
		 * DependencyInjector constructor.
		 * @param mixed $components Classes to include initially.
		 * @param int $flags Flags to control how this module behaves.
		 */
		public function __construct($components = null, int $flags = self::DEFAULT_FLAGS) {
			$this->flags = $flags;
			$this->classList = [];
			$this->bindingList = [];

			$this->addComponent($components);
		}

		public function addComponent($class) {
			// Null: Do nothing.
			if ($class === null)
				return;

			// Array: Loop all items and add them one by one.
			if (is_array($class)) {
				foreach ($class as $classItem)
					$this->addComponent($classItem);

				return;
			}

			// String: Class name, add to the internal class list.
			if (is_string($class)) {
				if (array_key_exists($class, $this->classList))
					throw new KrameWorkDependencyInjectorException("A class named '%s' has already been added to the injector.", $class);

				$this->classList[$class] = null;

				if ($this->flags & self::BIND_INTERFACES)
					$this->bindInterfaces($class);

				return;
			}

			// Object: Extract class and add that.
			if (is_object($class)) {
				$className = get_class($class);
				if (array_key_exists($className, $this->classList)) {
					$node = $this->classList[$className];

					if (!is_array($node))
						$node = [$node];

					$node[] = $class;
					$this->classList[$className] = $node;
				} else {
					$this->classList[$className] = $class;
				}

				if ($this->flags & self::BIND_INTERFACES)
					$this->bindInterfaces($className);

				return;
			}
		}

		/**
		 * Bind an interface to the given class. Class will also be added as a component.
		 * @param string $interface Name of the interface to bind the class to.
		 * @param string|object $class
		 */
		public function addBinding(string $interface, $class) {
			$this->bindInterface($interface, $class);
			$this->addComponent($class);
		}

		/**
		 * Resolve a given class name.
		 * @param mixed $class
		 * @param array $output
		 * @return string|\string[]
		 * @throws KrameWorkDependencyInjectorException
		 */
		public function resolveClassName($class, array &$output = null) {
			$classes = &$output ?? [];

			// Array given, loop elements and process.
			if (is_array($class)) {
				foreach ($class as $node)
					$this->resolveClassName($node, $classes);

				return $classes;
			}

			// Resolve class name from object.
			if (is_object($class))
				$class = get_class($class);

			// If we don't have a string at this point, we can't do much with it.
			if (!is_string($class))
				throw new KrameWorkDependencyInjectorException("Unable to resolve class(es) for type '%s'", gettype($class));

			// Check interface bindings for the class.
			if (array_key_exists($class, $this->bindingList))
				return $this->resolveClassName($this->bindingList[$class], $classes);

			$classes[] = $class;
			return $class;
		}

		/**
		 * Get a constructed component from the injector.
		 * @param string $className Name of the class to retrieve.
		 * @param bool $add If true, will attempt to add class if missing.
		 * @return object
		 * @throws KrameWorkDependencyInjectorException
		 */
		public function getComponent(string $className, bool $add = false) {
			$resolve = $this->resolveClassName($className);

			// getComponent() should only ever return a single component.
			if (is_array($resolve) || (array_key_exists($resolve, $this->classList) && is_array($this->classList[$resolve])))
				throw new KrameWorkDependencyInjectorException("Class '%s' resolves to multiple classes. Consider getComponents() instead.", $className);

			// Check if component is missing and react according to $add.
			if (!array_key_exists($resolve, $this->classList)) {
				if ($add)
					$this->addComponent($resolve);
				else
					throw new KrameWorkDependencyInjectorException("Class '%s' has not been added to the injector.", $resolve);
			}

			// Return cached instance, or construct new one.
			return $this->classList[$resolve] ?? $this->constructComponent($resolve);
		}

		/**
		 * Retrieve components for a class that has duplicate instances.
		 * @param string $className
		 * @param bool $add
		 * @return array
		 */
		public function getComponents(string $className, bool $add = false) {
			$resolves = $this->resolveClassName($className);
			if (!is_array($resolves))
				$resolves = [$resolves];

			$objects = [];
			foreach ($resolves as $resolve) {
				// We don't have an instance of this class yet.
				if (!array_key_exists($resolve, $this->classList)) {
					if ($add)
						$this->addComponent($resolve);
					else
						continue;
				}

				$components = $this->classList[$resolve] ?? $this->constructComponent($resolve);
				if (is_array($components))
					foreach ($components as $component)
						$objects[] = $component;
				else
					$objects[] = $components;
			}
			return $objects;
		}

		/**
		 * Construct a class by the given name.
		 * @param string $className Name of the class to construct.
		 * @return object
		 * @throws KrameWorkDependencyInjectorException
		 */
		public function constructComponent(string $className) {
			$class = new \ReflectionClass($className);

			if (!$class->isInstantiable())
				throw new KrameWorkDependencyInjectorException("Class '%s' cannot be instantiated!", $className);

			$inject = [];
			$constructor = $class->getConstructor();
			$object = $class->newInstanceWithoutConstructor();

			// Handle construction.
			if ($constructor) {
				// Process all parameters for the constructor.
				foreach ($constructor->getParameters() as $param) {
					$paramClass = $param->getClass();

					// Ensure parameter has a class.
					if ($paramClass === null)
						throw new KrameWorkDependencyInjectorException("Constructor for '%s' contains parameters with an undefined class.", $className);

					$paramClassName = $paramClass->getName();
					if ($paramClassName == $className)
						throw new KrameWorkDependencyInjectorException("Cyclic dependency occurred when constructing '%s'", $className);

					$inject[] = $this->getComponent($paramClassName, $this->flags & self::AUTO_ADD_DEPENDENCIES);
				}

				call_user_func_array([$object, "__construct"], $inject);
			}

			$this->classList[$className] = $object;
			return $object;
		}

		/**
		 * Detect interfaces for a class and bind them to it.
		 * @param string $className Name of the class to look-up interfaces for.
		 */
		public function bindInterfaces(string $className) {
			$class = new \ReflectionClass($className);
			foreach ($class->getInterfaceNames() as $interface)
				$this->bindInterface($interface, $className);
		}

		/**
		 * Bind an interface to the given class.
		 * @param string $interface
		 * @param $class
		 * @throws KrameWorkDependencyInjectorException
		 */
		public function bindInterface(string $interface, $class) {
			if (is_object($class))
				$class = get_class($class);

			if (!is_string($class))
				throw new KrameWorkDependencyInjectorException("Cannot create interface binding using type '%s'", gettype($class));

			if (array_key_exists($interface, $this->bindingList)) {
				$node = $this->bindingList[$interface];

				if (!is_array($node))
					$node = [$node];

				$node[] = $class;
				$this->bindingList[$interface] = $node;
			} else {
				$this->bindingList[$interface] = $class;
			}
		}

		/**
		 * @var array
		 */
		private $classList;

		/**
		 * @var array
		 */
		private $bindingList;

		/**
		 * @var int
		 */
		private $flags;

		/**
		 * @var AutoLoader
		 */
		private $autoLoader;
	}