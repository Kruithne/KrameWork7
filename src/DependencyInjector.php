<?php
	/*
	 * Copyright (c) 2017 Kruithne (kruithne@gmail.com)
	 * https://github.com/Kruithne/KrameWork7
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

	namespace KrameWork;

	class DuplicateClassException extends \Exception {}
	class ClassResolutionException extends \Exception {}
	class ClassInstantiationException extends \Exception {}
	class InterfaceBindingException extends \Exception {}

	/**
	 * Class DependencyInjector
	 * @package KrameWork\DI
	 */
	class DependencyInjector
	{
		const AUTO_BIND_INTERFACES = 0x1;
		const AUTO_ADD_DEPENDENCIES = 0x2;
		const DEFAULT_FLAGS = self::AUTO_BIND_INTERFACES | self::AUTO_ADD_DEPENDENCIES;

		/**
		 * DependencyInjector constructor.
		 * @param int $flags Flags to control how this module behaves.
		 * @param array $components Initial components.
		 * @param array $bindings Initial bindings.
		 */
		public function __construct(int $flags = self::DEFAULT_FLAGS, array $components = null, array $bindings = null) {
			$this->flags = $flags;
			$this->classList = [];
			$this->bindingList = [];

			// Add initial components.
			if ($components !== null)
				$this->addComponent($components);

			// Add initial interface bindings.
			if ($bindings)
				foreach ($bindings as $interface => $class)
					$this->bind($interface, $class);

			// If DependencyInjector is requested, we should return our own instance.
			$this->addComponent($this);
		}

		/**
		 * Add a component to the injector.
		 * @param string|array|object $class
		 * @throws DuplicateClassException
		 */
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
					throw new DuplicateClassException("Duplicate class added to injector: " . $class);

				$this->classList[$class] = null;

				if ($this->flags & self::AUTO_BIND_INTERFACES)
					$this->bindInterfaces($class);

				return;
			}

			// Object: Extract class and add that.
			if (is_object($class)) {
				$className = get_class($class);
				if (array_key_exists($className, $this->classList))
					throw new DuplicateClassException("Duplicate class added to injector: " . $className);

				$this->classList[$className] = $class;

				if ($this->flags & self::AUTO_BIND_INTERFACES)
					$this->bindInterfaces($className);

				return;
			}
		}

		/**
		 * Resolve a given class name.
		 * @param mixed $class
		 * @param array $output
		 * @return string|string[]
		 * @throws ClassResolutionException
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
				throw new ClassResolutionException("Unable to resolve class for: " . gettype($class));

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
		 * @throws ClassResolutionException
		 */
		public function getComponent(string $className, bool $add = false) {
			$resolve = $this->resolveClassName($className);

			// getComponent() should only ever return a single component.
			if (is_array($resolve) || (array_key_exists($resolve, $this->classList) && is_array($this->classList[$resolve])))
				throw new ClassResolutionException("Injector contains multiple resolutions for class: " . $className);

			// Check if component is missing and react according to $add.
			if (!array_key_exists($resolve, $this->classList)) {
				if ($add)
					$this->addComponent($resolve);
				else
					throw new ClassResolutionException("Injector does not have valid match for class: " . $resolve);
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
		 * @throws ClassInstantiationException
		 */
		public function constructComponent(string $className) {
			$class = new \ReflectionClass($className);

			if (!$class->isInstantiable())
				throw new ClassInstantiationException("Non-instantiable class: " . $className);

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
						throw new ClassInstantiationException("Constructor contains undefined parameter: " . $className);

					$paramClassName = $paramClass->getName();
					if ($paramClassName == $className)
						throw new ClassInstantiationException("Cyclic dependency in class: " . $className);

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
		private function bindInterfaces(string $className) {
			$class = new \ReflectionClass($className);
			foreach ($class->getInterfaceNames() as $interface)
				$this->bind($interface, $className);
		}

		/**
		 * Bind an interface to the given class.
		 * @param string $interface
		 * @param $class
		 * @throws InterfaceBindingException
		 */
		public function bind(string $interface, $class) {
			if (is_object($class))
				$class = get_class($class);

			if (!is_string($class))
				throw new InterfaceBindingException("Invalid input for interface binding: " . gettype($class));

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
	}