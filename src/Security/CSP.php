<?php
	namespace KrameWork\Security;

	class CSP
	{
		const SOURCE_NONE = '\'none\''; // Nothing allowed.
		const SOURCE_SELF = '\'self\''; // Same-domain only.
		const SOURCE_INLINE = '\'unsafe-inline\''; // Inline allowed.
		const SOURCE_EVAL = '\'unsafe-eval\''; // Inline allowed (with eval).
		const SOURCE_HTTPS = 'https:'; // Apply rule to HTTPS protocol.
		const SOURCE_DATA = 'data:'; // Apply rule to DATA protocol.

		const DIRECTIVE_DEFAULT = 'default-src'; // Fallback.
		const DIRECTIVE_BASE = 'base-uri'; // <base> URI.
		const DIRECTIVE_CHILD = 'child-src'; // Workers/embedded frames.
		const DIRECTIVE_CONNECT = 'connect-src'; // XHR, WebSockets, EventSource.
		const DIRECTIVE_FONT = 'font-src'; // Fonts
		const DIRECTIVE_FORM = 'form-action'; // Valid endpoints for forms.
		const DIRECTIVE_ANCESTORS = 'frame-ancestors'; // frame, iframe, embed, applet
		const DIRECTIVE_IMAGE = 'img-src'; // Images
		const DIRECTIVE_MEDIA = 'media-src'; // Video/audio.
		const DIRECTIVE_OBJECT = 'object-src'; // Flash/multimedia plug-ins.
		const DIRECTIVE_PLUGIN = 'plugin-types'; // Invokable plug-in types.
		const DIRECTIVE_REPORT = 'report-uri'; // URL for browser to report to.
		const DIRECTIVE_STYLE = 'style-src'; // Stylesheets.
		const DIRECTIVE_SCRIPT = 'script-src'; // Scripts.
		const DIRECTIVE_UPGRADE = 'upgrade-insecure-requests'; // Upgrade HTTP to HTTPS

		/**
		 * CSP constructor.
		 *
		 * @api __construct
		 */
		public function __construct() {
			$this->directives = [];
			$this->directives[self::DIRECTIVE_DEFAULT] = self::SOURCE_SELF;
		}

		/**
		 * Add a directive to this policy.
		 *
		 * @api add
		 * @param array|string $directives Directive (use CSP:: constants).
		 * @param array|string $source Source directive.
		 */
		public function add($directives, $source) {
			if (is_array($source))
				$source = implode(' ', $source);

			foreach (is_array($directives) ? $directives : [$directives] as $directive)
				$this->directives[$directive] = $source;
		}

		/**
		 * Set this policy as the Content-Security-Policy header.
		 *
		 * @api apply
		 */
		public function apply() {
			header($this->__toString());
		}

		/**
		 * Compile this to a header string.
		 *
		 * @return string
		 * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
		 */
		function __toString() {
			$parts = [];
			foreach ($this->directives as $directive => $source)
				$parts[] = $directive . ' ' . $source;

			return 'Content-Security-Policy: ' . implode('; ', $parts);
		}

		private $directives;
	}