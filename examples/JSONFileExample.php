<?php
	use KrameWork\Storage\JSONFile;

	// Inherits functionality from BaseFile and uses an internal KeyValueContainer.
	// Check examples/documentation on those for more in-depth information.

	// Load a JSON file. By default, this will use an internal KeyValueContainer to store it.
	// Will throw a KrameWorkFileException if the file does not exist, or cannot be read.

	$file = new JSONFile("bigData.json");

	// Set options for this wrapper.
	$file->setOptions(JSON_BIGINT_AS_STRING);

	// Access JSON values.
	print($file->myValue);

	// Assign values.
	$file->myValue = 42;

	// Save to file encoded as JSON.
	$file->save();