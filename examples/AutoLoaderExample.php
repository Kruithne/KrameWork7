<?php
	use \KrameWork\AutoLoader;

	// Setting up the auto-loader is as simple as creating a new instance of it.
	// This will operate with default behavior. Check below to see what the default
	// behavior is, and how you can tweak it.
	new AutoLoader();

	// The AutoLoader class comes with three options, all of which are enabled by default.
	// INCLUDE_WORKING_DIRECTORY - The working directory will be included as an auto-load source.
	// INCLUDE_KRAMEWORK_DIRECTORY - The "KrameWork" namespace will be automatically mapped to the KrameWork source directory.
	// RECURSIVE_SOURCING - Auto-loader will search recursively through source directories to find classes.
	// These options can be controlled by a bit-mask as the third parameter of the constructor, see below.

	// Create an auto-loader with recursive sourcing disabled.
	new AutoLoader(null, null, AutoLoader::DEFAULT_FLAGS & ~AutoLoader::RECURSIVE_SOURCING);

	// Create an auto-loader with all defaults disabled.
	new AutoLoader(null, null, 0);

	// Create an auto-loader with only given options (recursive and KrameWork loading).
	new AutoLoader(null, null, AutoLoader::RECURSIVE_SOURCING & AutoLoader::INCLUDE_KRAMEWORK_DIRECTORY);

	// The first parameter for the AutoLoader takes an array filled with directories that can be
	// searched for auto-loading. Here we add someDirectory/myClasses to be auto-loaded from.
	new AutoLoader(["someDirectory/myClasses"]);

	// NOTES:
	// - Directory separators will be automatically converted to match the current environment.
	// - Paths that cannot be resolved will be discarded without an error.

	// Extensions
	// The second parameter of the AutoLoader constructor takes an array to define which file extensions can be
	// auto-loaded. If left omitted (or null) then by default, it will load files with the 'php' extension.
	// The leading period is not required (however does not break things if included).
	new AutoLoader(null, ["php", "ext", ".dat"]);

	// Namespaces
	// If your directory structure matches your namespace design, then nothing extra needs to be done to the
	// auto-loader for support.

	// Example, where MyNameSpace\TestClass is stored in files/MyNameSpace/TestClass.php
	new AutoLoader(["files"], null, 0);

	// With RECURSIVE_SOURCING and INCLUDE_WORKING_DIRECTORY (both enabled by default), the following works the same
	// since "files" is a sub-directory of the working directory, included by default, and recursive sourcing is on by default.
	new AutoLoader();