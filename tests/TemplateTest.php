<?php
	use KrameWork\MVC\InvalidTemplateException;
	use KrameWork\MVC\Template;
	require_once(__DIR__ . '/../src/MVC/Template.php');

	class TemplateTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test expected exception is thrown when template file is missing.
		 */
		public function testMissingFileTemplate() {
			try {
				new Template(__DIR__ . '/resources/TemplateTest/missingfile.php');
				$this->fail('Missing template file did not throw expected exception.');
			} catch (InvalidTemplateException $e) {
				// Expected.
				$this->assertTrue(true, 'The universe has stopped responding.');
			}
		}

		/**
		 * Test expected exception is thrown when template file is invalid.
		 */
		public function testInvalidFileTemplate() {
			try {
				new Template(__DIR__ . '/resources/TemplateTest');
				$this->fail('Invalid template file did not throw expected exception.');
			} catch (InvalidTemplateException $e) {
				// Expected.
				$this->assertTrue(true, 'The universe has stopped responding.');
			}
		}

		/**
		 * Test template getter/setter works as expected.
		 */
		public function testTemplateStorage() {
			$template = new Template(__DIR__ . '/resources/TemplateTest/template.php');
			$template->foo = 'bar';
			$this->assertEquals('bar', $template->foo);
			unset($template);
		}

		/**
		 * Test template compilation functions correctly.
		 */
		public function testTemplateCompile() {
			$template = new Template(__DIR__ . '/resources/TemplateTest/template.php');
			$template->content = 'Hello, world!';

			$this->assertEquals('<b>Hello, world!</b>', $template->__toString());
			unset($template);
		}
	}