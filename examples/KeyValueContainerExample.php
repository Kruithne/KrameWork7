<?php
	use KrameWork\Storage\KeyValueContainer;

	// A KeyValueContainer is a simple class that contains assigned data by a
	// given key and can be serialized in various ways.

	// Create a new container.
	$container = new KeyValueContainer();

	// Assign a variable.
	$container->thing = 42;

	// Access assigned variables.
	print($container->thing);

	// Serialize the container to a string.
	$serialized = $container->serialize();

	// Load data from a serialized string (overwrites existing container data).
	$container->unserialize($serialized);

	// Access the internal data array.
	print_r($container->asArray());

	// Implements JsonSerializable, check JSONFile class for better JSON handling.
	$json = json_encode($container);