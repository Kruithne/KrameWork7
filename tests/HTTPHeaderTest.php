<?php
	use KrameWork\HTTP\HSTSHeader;
	use KrameWork\HTTP\HTTPHeader;
	use KrameWork\HTTP\MIMEValidationHeader;
	use KrameWork\HTTP\ReferrerPolicyHeader;
	use KrameWork\HTTP\XFrameHeader;
	use KrameWork\HTTP\XSSProtectionHeader;

	require_once(__DIR__ . '/../src/HTTP/HTTPHeader.php');
	require_once(__DIR__ . '/../src/HTTP/XSSProtectionHeader.php');
	require_once(__DIR__ . '/../src/HTTP/MIMEValidationHeader.php');
	require_once(__DIR__ . '/../src/HTTP/XFrameHeader.php');
	require_once(__DIR__ . '/../src/HTTP/HSTSHeader.php');
	require_once(__DIR__ . '/../src/HTTP/ReferrerPolicyHeader.php');

	class HTTPHeaderTest extends PHPUnit\Framework\TestCase
	{
		/**
		 * Test functionality of the underlying HTTPHeader class.
		 */
		public function testBaseClass() {
			$class = new class extends HTTPHeader {
				/**
				 * Get the field name for this header.
				 *
				 * @api getFieldName
				 * @return string
				 */
				public function getFieldName(): string {
					return 'TestHeader';
				}

				/**
				 * Get the field value for this header.
				 *
				 * @api getFieldValue
				 * @return string
				 */
				public function getFieldValue(): string {
					return 'foo; bar';
				}
			};

			$this->assertEquals('TestHeader', $class->getFieldName());
			$this->assertEquals('foo; bar', $class->getFieldValue());
			$this->assertEquals('TestHeader: foo; bar', $class->__toString());
			unset($class);
		}

		/**
		 * Test HTTP header: XSSProtectionHeader
		 */
		public function testXSSProtectionHeader() {
			$header = new XSSProtectionHeader();
			$this->assertEquals('X-XSS-Protection', $header->getFieldName());
			$this->assertEquals('1; mode=block', $header->getFieldValue());
			$this->assertEquals('X-XSS-Protection: 1; mode=block', $header->__toString());
			unset($header);
		}

		/**
		 * Test HTTP header: MIMEValidationHeader
		 */
		public function testMIMEValidationHeader() {
			$header = new MIMEValidationHeader();
			$this->assertEquals('X-Content-Type-Options', $header->getFieldName());
			$this->assertEquals('nosniff', $header->getFieldValue());
			$this->assertEquals('X-Content-Type-Options: nosniff', $header->__toString());
			unset($header);
		}

		/**
		 * Test HTTP header: XFrameHeader
		 */
		public function testXFrameHeader() {
			// Default behavior.
			$header = new XFrameHeader();
			$this->assertEquals('X-Frame-Options: DENY', $header->__toString());

			// Constructor DENY
			$header = new XFrameHeader(XFrameHeader::DENY);
			$this->assertEquals('X-Frame-Options: DENY', $header->__toString());

			// Constructor SAME_ORIGIN
			$header = new XFrameHeader(XFrameHeader::SAME_ORIGIN);
			$this->assertEquals('X-Frame-Options: SAMEORIGIN', $header->__toString());

			// Specific DENY
			$header = new XFrameHeader(XFrameHeader::SAME_ORIGIN);
			$header->setOption(XFrameHeader::DENY);
			$this->assertEquals('X-Frame-Options: DENY', $header->__toString());

			// Specific SAME_ORIGIN
			$header = new XFrameHeader();
			$header->setOption(XFrameHeader::SAME_ORIGIN);
			$this->assertEquals('X-Frame-Options: SAMEORIGIN', $header->__toString());

			unset($header);
		}

		/**
		 * Test HTTP header: HSTSHeader
		 */
		public function testHSTSHeader() {
			$tests = [
				'max-age=63072000; includeSubDomains; preload' => new HSTSHeader(),
				'max-age=126144000; includeSubDomains; preload' => new HSTSHeader(126144000),
				'max-age=126144000; preload' => new HSTSHeader(126144000, false, true),
				'max-age=126144000; includeSubDomains' => new HSTSHeader(126144000, true, false),
				'max-age=126144000' => new HSTSHeader(126144000, false, false)
			];

			foreach ($tests as $expected => $object)
				$this->assertEquals('Strict-Transport-Security: ' . $expected, $object->__toString());
		}

		/**
		 * Test HTTP header: ReferrerPolicyHeader
		 */
		public function testReferrerPolicyHeader() {
			$reflect = new ReflectionClass('KrameWork\HTTP\ReferrerPolicyHeader');
			$constants = $reflect->getConstants();

			foreach ($constants as $constant => $value) {
				$header = new ReferrerPolicyHeader($value);
				$this->assertEquals('Referrer-Policy: ' . $value, $header->__toString());

				$header = new ReferrerPolicyHeader();
				$header->setValue($value);
				$this->assertEquals('Referrer-Policy: ' . $value, $header->__toString());
			}
		}
	}