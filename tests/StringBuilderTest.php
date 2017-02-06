<?php
	use KrameWork\Utils\StringBuilder;

	require_once(__DIR__ . "/../src/Utils/StringBuilder.php");

	class StringBuilderTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * Test the default internal string of the builder.
		 */
		public function testDefault() {
			$builder = new StringBuilder();
			$this->assertEquals("", $builder->__toString());
			unset($builder);
		}

		/**
		 * Test the StringBuilder append() functionality.
		 */
		public function testAppend() {
			$builder = new StringBuilder();
			$builder->append("Hello")->append("World");

			$this->assertEquals("HelloWorld", $builder->__toString());
			unset($builder);
		}

		/**
		 * Test appending objects to the StringBuilder.
		 */
		public function testAppendObject() {
			$obj = new class { public function __toString() { return "Boots"; } };
			$builder = new StringBuilder();
			$builder->append("Cats", $obj);

			$this->assertEquals("CatsBoots", $builder->__toString());
			unset($builder, $obj);
		}

		/**
		 * Test appending arrays to the StringBuilder.
		 */
		public function testAppendArray() {
			$arr = ["Hairy", "Bees"];
			$builder = new StringBuilder();
			$builder->append($arr, "Help");

			$this->assertEquals("HairyBeesHelp", $builder->__toString());
			unset($builder, $arr);
		}

		/**
		 * Test the StringBuilder prepend() functionality.
		 */
		public function testPrepend() {
			$builder = new StringBuilder();
			$builder->prepend("Hello")->prepend("World");

			$this->assertEquals("WorldHello", $builder->__toString());
			unset($builder);
		}

		/**
		 * Test the StringBuilder repeat() append functionality.
		 */
		public function testRepeatAppend() {
			$builder = new StringBuilder();
			$builder->append("Start")->repeat("Test", 3, true);

			$this->assertEquals("StartTestTestTest", $builder->__toString());
			unset($builder);
		}

		/**
		 * Test the StringBuilder repeat() prepend functionality.
		 */
		public function testRepeatPrepend() {
			$builder = new StringBuilder();
			$builder->append("End")->repeat("Test", 3, false);

			$this->assertEquals("TestTestTestEnd", $builder->__toString());
			unset($builder);
		}

		/**
		 * Test the initial value of the StringBuilder.
		 */
		public function testInitialValue() {
			$builder = new StringBuilder("Hello");
			$builder->append("World");

			$this->assertEquals("HelloWorld", $builder->__toString());
			unset($builder);
		}

		/**
		 * Test appendf functionality.
		 */
		public function testAppendf() {
			$builder = new StringBuilder("Fact: ");
			$builder->appendf("I am %s!", "Batman");

			$this->assertEquals("Fact: I am Batman!", $builder->__toString());
			unset($builder);
		}

		/**
		 * Test prependf functionality.
		 */
		public function testPrependf() {
			$builder = new StringBuilder(" ...Or am I?");
			$builder->prependf("I am %s!", "Batman");

			$this->assertEquals("I am Batman! ...Or am I?", $builder->__toString());
			unset($builder);
		}
	}