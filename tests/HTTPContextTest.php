<?php
	use KrameWork\HTTPContext;
	require_once(__DIR__ . "/../src/HTTPContext.php");

	class HTTPContextTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test HTTPContext->getContentLength().
		 */
		public function testContentLength() {
			// Setup
			$CONTENT_LENGTH = "CONTENT_LENGTH";
			$orig = $_SERVER[$CONTENT_LENGTH] ?? null;
			$_SERVER[$CONTENT_LENGTH] = 52823;

			// Testing
			$http = new HTTPContext();
			$length = $http->getContentLength();

			$this->assertTrue(is_int($length), "Length is not an integer.");
			$this->assertEquals(52823, $length, "Length was an unexpected value.");

			// Default return value.
			$_SERVER[$CONTENT_LENGTH] = null;
			$this->assertEquals(0, $http->getContentLength());

			// Cleanup
			unset($http);
			$_SERVER[$CONTENT_LENGTH] = $orig;
		}

		/**
		 * Test HTTPContext->getUserAgent().
		 */
		public function testUserAgent() {
			// Setup
			$HTTP_USER_AGENT = "HTTP_USER_AGENT";
			$userAgent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36";
			$orig = $_SERVER[$HTTP_USER_AGENT] ?? null;
			$_SERVER[$HTTP_USER_AGENT] = $userAgent;

			// Testing
			$http = new HTTPContext();
			$this->assertEquals($userAgent, $http->getUserAgent());

			// Default return value.
			$_SERVER[$HTTP_USER_AGENT] = null;
			$this->assertEquals("Unknown", $http->getUserAgent());

			// Cleanup
			unset($http);
			$_SERVER[$HTTP_USER_AGENT] = $orig;
		}

		/**
		 * Test HTTPContext->getContentType().
		 */
		public function testContentType() {
			// Setup
			$CONTENT_TYPE = "CONTENT_TYPE";
			$orig = $_SERVER[$CONTENT_TYPE] ?? null;

			$contentTypes = [
				"multipart/form-data" => "multipart/form-data; boundary=----WebKitFormBoundaryTvTAwcMPGO12XtFA",
				"text/plain" => "text/plain",
				"application/x-www-form-urlencoded" => "application/x-www-form-urlencoded;parameters"
			];

			// Testing
			$http = new HTTPContext();
			foreach ($contentTypes as $expected => $testValue) {
				$fresh = new HTTPContext();
				$_SERVER[$CONTENT_TYPE] = $testValue;

				$this->assertEquals($http->getContentType(true), $testValue);
				$this->assertEquals($fresh->getContentType(true), $testValue);
				$this->assertEquals($http->getContentType(false), $expected);
				$this->assertEquals($fresh->getContentType(false), $expected);

				unset($fresh);
			}

			// Default return value.
			$_SERVER[$CONTENT_TYPE] = null;
			$this->assertEquals("text/plain", $http->getContentType());

			// Cleanup
			unset($http);
			$_SERVER[$CONTENT_TYPE] = $orig;
		}

		/**
		 * Test HTTPContext->getReferer().
		 */
		public function testReferer() {
			// Setup
			$HTTP_REFERER = "HTTP_REFERER";
			$ref = "https://www.google.co.uk/search?q=awesome+frameworks&oq=awesome+frameworks&sourceid=chrome&ie=UTF-8";
			$orig = $_SERVER[$HTTP_REFERER] ?? null;
			$_SERVER[$HTTP_REFERER] = $ref;

			// Testing
			$http = new HTTPContext();
			$this->assertEquals($ref, $http->getReferer());

			// Default return value.
			$_SERVER[$HTTP_REFERER] = null;
			$this->assertEquals("", $http->getReferer());

			// Cleanup
			unset($http);
			$_SERVER[$HTTP_REFERER] = $orig;
		}

		/**
		 * Test HTTPContext->getRemoteAddress().
		 */
		public function testRemoteAddress() {
			// Setup
			$REMOTE_ADDR = "REMOTE_ADDR";
			$orig = $_SERVER[$REMOTE_ADDR] ?? null;
			$_SERVER[$REMOTE_ADDR] = "127.0.0.1";

			// Testing
			$http = new HTTPContext();
			$this->assertEquals("127.0.0.1", $http->getRemoteAddress());

			// Default return value.
			$_SERVER[$REMOTE_ADDR] = null;
			$this->assertEquals("", $http->getRemoteAddress());

			// Cleanup
			unset($http);
			$_SERVER[$REMOTE_ADDR] = $orig;
		}

		/**
		 * Test HTTPContext->getRequestURI().
		 */
		public function testRequestURI() {
			// Setup
			$uri = "/status/example/post_test.php?test[]=1&test[]=2";
			$REQUEST_URI = "REQUEST_URI";
			$orig = $_SERVER[$REQUEST_URI] ?? null;
			$_SERVER[$REQUEST_URI] = $uri;

			// Testing
			$http = new HTTPContext();
			$this->assertEquals($uri, $http->getRequestURI());

			// Default return value.
			$_SERVER[$REQUEST_URI] = null;
			$this->assertEquals("", $http->getRequestURI());

			// Cleanup
			unset($http);
			$_SERVER[$REQUEST_URI] = $orig;
		}

		/**
		 * Test HTTPContext->getRequestMethod().
		 */
		public function testRequestMethod() {
			// Setup
			$REQUEST_METHOD = "REQUEST_METHOD";
			$orig = $_SERVER[$REQUEST_METHOD] ?? null;
			$_SERVER[$REQUEST_METHOD] = "CONNECT";

			// Testing
			$http = new HTTPContext();
			$this->assertEquals("CONNECT", $http->getRequestMethod());

			// Default return value.
			$_SERVER[$REQUEST_METHOD] = null;
			$this->assertEquals("GET", $http->getRequestMethod());

			// Cleanup
			unset($http);
			$_SERVER[$REQUEST_METHOD] = $orig;
		}

		/**
		 * Test HTTPContext->getQueryString().
		 */
		public function testQueryString() {
			// Setup
			$queryString = "test[]=1&test[]=2&thing=stuff";
			$QUERY_STRING = "QUERY_STRING";
			$orig = $_SERVER[$QUERY_STRING] ?? null;
			$_SERVER[$QUERY_STRING] = $queryString;

			// Testing
			$http = new HTTPContext();
			$this->assertEquals($queryString, $http->getQueryString());

			// Default return value
			$_SERVER[$QUERY_STRING] = null;
			$this->assertEquals("", $http->getQueryString());

			// Cleanup
			unset($http);
			$_SERVER[$QUERY_STRING] = $orig;
		}

		/**
		 * Test HTTPContext->getQueryDataValue().
		 */
		public function testQueryData() {
			// Setup
			$QUERY_STRING = "QUERY_STRING";
			$orig = $_SERVER[$QUERY_STRING] ?? null;
			$_SERVER[$QUERY_STRING] = "test[]=1&test[]=2&thing=stuff";

			// Testing
			$http = new HTTPContext();
			$test = $http->getQueryDataValue("test");

			$this->assertTrue(is_array($test), "\$test is not an array.");
			$this->assertEquals(2, count($test), "\$test is not expected size.");
			$this->assertEquals("stuff", $http->getQueryDataValue("thing"));

			// Cleanup
			unset($http);
			$_SERVER[$QUERY_STRING] = $orig;
		}

		/**
		 * Test HTTPContext->getQueryDataValues().
		 */
		public function testQueryDataMultiple() {
			// Setup
			$QUERY_STRING = "QUERY_STRING";
			$orig = $_SERVER[$QUERY_STRING] ?? null;
			$_SERVER[$QUERY_STRING] = "test[]=1&test[]=2&thing=stuff";

			// Testing
			$http = new HTTPContext();
			list($test, $stuff) = $http->getQueryDataValues("test", "thing");

			$this->assertTrue(is_array($test), "\$test is not an array.");
			$this->assertEquals(2, count($test), "\$test is not expected size.");
			$this->assertEquals("stuff", $stuff);

			// Cleanup
			unset($http);
			$_SERVER[$QUERY_STRING] = $orig;
		}

		/**
		 * Test HTTPContext->hasFiles() and HTTPContext->getFiles().
		 */
		public function testFileAccess() {
			// Setup
			$orig = $_FILES;
			$_FILES = [ // Emulation of a possible array file upload (with one uncaught failure).
				"test" => [
					"name" => [
						"(15)_Twitter_-_Google_Chrome_2016-07-27_17-51-04.png",
						"2015-11-01_21-15-13.png",
						"this-file_does_not.exist"
					],
					"type" => ["image/png", "image/png", "text/plain"],
					"tmp_name" => [
						__DIR__ . "/resources/HTTPContextTest/tmp/phpKpG7lj",
						__DIR__ . "/resources/HTTPContextTest/tmp/phpbF6s1h",
						__DIR__ . "/resources/HTTPContextTest/tmp/phpn3jDjf"
					],
					"error" => [0, 0, UPLOAD_ERR_NO_FILE],
					"size" => [87781, 117552, 373623]
				]
			];

			// Testing
			$http = new HTTPContext();
			$this->assertFalse($http->hasFile("doesNotExist"), "Context claims non-existing file exists.");
			$this->assertTrue($http->hasFile("test"), "Context claims existing file does not exist.");

			// Test Wrappers.
			$testFiles = $http->getFiles("test", true);
			$this->assertTrue(is_array($testFiles), "Context did not return array as expected.");
			$this->assertCount(3, $testFiles, "Context did not return correct amount of files.");

			foreach ($testFiles as $file) {
				$this->assertInstanceOf("KrameWork\\Storage\\UploadedFile", $file, "File was not wrapped.");

				if ($file->getErrorCode() != UPLOAD_ERR_OK)
					$this->assertFalse($file->isValid(), "File should be invalid when an error occurs.");
				else
					$this->assertTrue($file->isValid(), "File was expected to be valid.");
			}

			// Test Without Wrappers.
			$testFiles = $http->getFiles("test", false);
			$this->assertTrue(is_array($testFiles), "Context did not return array as expected.");
			$this->assertCount(3, $testFiles, "Context did not return correct amount of files.");

			// Cleanup
			$_FILES = $orig;
		}
	}