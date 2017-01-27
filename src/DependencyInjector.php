<?php
	namespace KrameWork;

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
		const DEFAULT_FLAGS = self::BIND_INTERFACES;

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
					throw new KrameWorkDependencyInjectorException("A class named %s has already been added to the injector.", $class);

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
		 * Bind an interface to the given class. Class will also be added as a component.
		 * @param string $interface Name of the interface to bind the class to.
		 * @param string|object $class
		 */
		public function addBinding(string $interface, $class) {
			$this->bindInterface($interface, $class);
			$this->addComponent($class);
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