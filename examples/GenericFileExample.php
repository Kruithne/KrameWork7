<?php
	use KrameWork\Storage\GenericFile;

	// Create a file wrapper and read from disk.
	// Will throw KrameWorkFileException if the file does not exist or cannot be read.

	$file = new GenericFile("myFile.txt");

	// Access the loaded data.
	print($file->getData());

	// Create a file wrapper without reading from disk.
	$file = new GenericFile("myFile.txt", false);

	// Check if the file actually exists before reading.
	if ($file->exists())
		$file->read(); // Read file data into the wrapper.

	// Overwrite the data.
	$file->setData("New data!");

	// Save to myFile.txt
	$file->save();

	// Create a file wrapper without a file.
	$file = new GenericFile();

	// Write some data to the file.
	$file->setData("Hello, world!");

	// Save to a specific file.
	$file->save("anotherFile.txt");