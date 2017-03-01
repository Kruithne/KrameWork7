<?php
	use KrameWork\HTTP\Request\ResponseNotAvailableException;
	use KrameWork\HTTP\Request\WebRequest;
	require_once(__DIR__ . '/../src/HTTP/Request/WebRequest.php');
	require_once(__DIR__ . '/../src/HTTP/XSSProtectionHeader.php');

	class WebRequestTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test basic __get/__set usage on the WebRequest class.
		 */
		public function testDataStorage() {
			$req = new WebRequest('localhost');
			$req->a = 42;
			$req->b = 'foo bar';
			$req->c = false;

			$this->assertEquals(42, $req->a);
			$this->assertEquals('foo bar', $req->b);
			$this->assertEquals('false', $req->c);

			unset($req);
		}

		/**
		 * Test default header/hasHeader() functionality.
		 */
		public function testDefaultHeader() {
			$req = new WebRequest('localhost');

			// hasHeader checking.
			$this->assertTrue($req->hasHeader('Content-type'));
			$this->assertTrue($req->hasHeader('content-type'));
			$this->assertTrue($req->hasHeader('CONTENT-TYPE'));

			unset($req);
		}

		/**
		 * Test header retrieval through getHeader().
		 */
		public function testHeaderRetrieval() {
			$req = new WebRequest('localhost');

			$expectedName = 'Content-type';
			$expectedValue = 'application/x-www-form-urlencoded';

			$invalidCheck = $req->getHeader('Non-Set-Header');
			$this->assertNull($invalidCheck);

			$check1 = $req->getHeader('Content-type');
			$this->assertTrue(is_array($check1));
			$this->assertEquals($expectedName, $check1[0]);
			$this->assertEquals($expectedValue, $check1[1]);

			$check2 = $req->getHeader('content-type');
			$this->assertTrue(is_array($check2));
			$this->assertEquals($expectedName, $check1[0]);
			$this->assertEquals($expectedValue, $check1[1]);

			$check3 = $req->getHeader('CONTENT-TYPE');
			$this->assertTrue(is_array($check3));
			$this->assertEquals($expectedName, $check1[0]);
			$this->assertEquals($expectedValue, $check1[1]);

			unset($req);
		}

		/**
		 * Test functionality of setting a single header.
		 */
		public function testSingleHeaderSet() {
			$req = new WebRequest('localhost');

			$fieldName = 'Accept';
			$fieldValue = 'text/plain';

			// Ensure header is invalid before we start.
			$this->assertFalse($req->hasHeader($fieldName));
			$this->assertNull($req->getHeader($fieldName));

			$req->setHeader($fieldName, $fieldValue);
			$header = $req->getHeader($fieldName);

			$this->assertTrue(is_array($header));
			$this->assertEquals($fieldName, $header[0]);
			$this->assertEquals($fieldValue, $header[1]);

			unset($req);
		}

		/**
		 * Test functionality of setting multiple headers.
		 */
		public function testMultiHeaderSet() {
			$headers = [
				'Accept' => 'text/plain',
				'Cookie' => 'foo=bar',
				'Content-type' => 'application/json'
			];

			$req = new WebRequest('localhost');
			$req->setHeaders($headers);

			foreach ($headers as $fieldName => $fieldValue) {
				$this->assertTrue($req->hasHeader($fieldName));
				$header = $req->getHeader($fieldName);
				$this->assertTrue(is_array($header));
				$this->assertEquals($fieldName, $header[0]);
				$this->assertEquals($fieldValue, $header[1]);
			}

			unset($headers, $req);
		}

		/**
		 * Test functionality of setting an object header.
		 */
		public function testObjectHeaderSet() {
			$req = new WebRequest('localhost');
			$header = new \KrameWork\HTTP\XSSProtectionHeader();

			$this->assertFalse($req->hasHeader($header->getFieldName()));
			$req->setHeaderObject($header);
			$this->assertTrue($req->hasHeader($header->getFieldName()));

			$headerData = $req->getHeader($header->getFieldName());
			$this->assertTrue(is_array($headerData));
			$this->assertEquals($header->getFieldName(), $headerData[0]);
			$this->assertEquals($header->getFieldValue(), $headerData[1]);

			unset($header, $headerData, $req);
		}

		/**
		 * Test expected results of premature access.
		 */
		public function testPrematureAccess() {
			$req = new WebRequest('localhost');

			// Before send(), success() should return null.
			$this->assertNull($req->success());

			try {
				$req->getResponse();
				$this->fail('Expected exception not thrown.');
			} catch (ResponseNotAvailableException $e) {
				// Expected.
			}

			try {
				$req->__toString();
				$this->fail('Expected exception not thrown');
			} catch (ResponseNotAvailableException $e) {
				// Expected.
			}

			unset($req);
		}

		/**
		 * Test GET request without parameters.
		 */
		public function testGETRequestWithoutParameters() {
			$req = new WebRequest('https://jsonplaceholder.typicode.com/users', WebRequest::METHOD_GET);
			$req->setHeader('User-agent', 'KrameWork7 Unit Testing');

			$this->assertTrue($req->send());
			$this->assertTrue($req->success());

			$resp = $req->getResponse();
			$this->assertEquals('d5e312b3178945f9d52d106660949b0a2259d6a4', sha1($resp));

			unset($req, $resp);
		}

		/**
		 * Test GET request with parameters.
		 */
		public function testGETRequestWithParameters() {
			$req = new WebRequest('https://jsonplaceholder.typicode.com/users', WebRequest::METHOD_GET);
			$req->setHeader('User-agent', 'KrameWork7 Unit Testing');

			$req->id = 1;

			$this->assertTrue($req->send());
			$this->assertTrue($req->success());

			$resp = $req->getResponse();
			$this->assertEquals('97f1288f8cd89af8ab7b4726e620535834bee05f', sha1($resp));

			unset($req, $resp);
		}

		/**
		 * Test POST request without parameters.
		 */
		public function testPOSTRequestWithoutParameters() {
			$req = new WebRequest('https://jsonplaceholder.typicode.com/users', WebRequest::METHOD_POST);
			$req->setHeader('User-agent', 'KrameWork7 Unit Testing');

			$this->assertTrue($req->send());
			$this->assertTrue($req->success());

			$resp = json_decode($req->getResponse());
			$this->assertGreaterThan(0, $resp->id);

			unset($req, $resp);
		}

		/**
		 * Test POST request with parameters.
		 */
		public function testPOSTRequestWithParameters() {
			$req = new WebRequest('https://jsonplaceholder.typicode.com/users', WebRequest::METHOD_POST);
			$req->setHeader('User-agent', 'KrameWork7 Unit Testing');

			$req->foo = 'bar';
			$req->other = 42;

			$this->assertTrue($req->send());
			$this->assertTrue($req->success());

			$resp = json_decode($req->getResponse());
			$this->assertGreaterThan(0, $resp->id);
			$this->assertEquals('bar', $resp->foo);
			$this->assertEquals(42, $resp->other);

			unset($req, $resp);
		}
	}