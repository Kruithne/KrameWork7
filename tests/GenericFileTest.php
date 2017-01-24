<?php
	namespace KrameWork\Storage;
	require_once("src/Storage/BaseFile.php");
	require_once("src/Storage/GenericFile.php");

	class GenericFileTest extends \PHPUnit_Framework_TestCase {
		/**
		 * Test basic functionality of the class.
		 */
		public function testFoundation() {
			$file = new GenericFile(); // Create without initial file.
			$this->assertEquals("", $file->getData(), "Initial file data expected to be empty, was not!");
			$this->assertEquals("", $file->compile(), "Initial compiled data expected to be empty, was not!");

			$subject = "Today you are you! That is truer than true! There is no one alive who is you-er than you!";
			$file->setData($subject);

			$this->assertEquals($subject, $file->getData(), "File data did not match the original data we set.");
			$this->assertEquals($subject, $file->compile(), "Compiled file data did not match original data we set.");
		}
	}