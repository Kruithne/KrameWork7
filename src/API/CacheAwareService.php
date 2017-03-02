<?php
	/*
	 * Copyright (c) 2017 Kruithne (kruithne@gmail.com)
	 * https://github.com/Kruithne/KrameWork7
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all
	 * copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 */

	namespace KrameWork\API;

	/**
	 * Class ServiceResponse
	 * A service call response to encode as JSON and return to the caller
	 *
	 * @package KrameWork\API
	 * @author docpify <morten@runsafe.no>
	 */
	class ServiceResponse implements \JsonSerializable
	{
		/**
		 * ServiceResponse constructor.
		 *
		 * @api __construct
		 * @param bool $success
		 * @param mixed $data
		 */
		public function __construct(bool $success = true, $data = []) {
			$this->data = ['success' => $success, 'result' => $data];
		}

		/**
		 * Flag the request as succeeded
		 *
		 * @api success
		 * @return ServiceResponse
		 */
		public function success(): ServiceResponse {
			$this->data['success'] = true;
			return $this;
		}

		/**
		 * Flag the request as failed
		 *
		 * @api failure
		 * @return ServiceResponse
		 */
		public function failure(): ServiceResponse {
			$this->data['success'] = false;
			return $this;
		}

		/**
		 * Get a property from the result
		 *
		 * @api __get
		 * @param $name
		 * @return mixed
		 */
		public function __get($name) {
			return $this->data['result'][$name];
		}

		/**
		 * Set a property on the result
		 *
		 * @api __set
		 * @param $name
		 * @param $value
		 */
		public function __set($name, $value) {
			$this->data['result'][$name] = $value;
		}

		/**
		 * Specify data which should be serialized to JSON
		 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
		 * @return mixed data which can be serialized by <b>json_encode</b>,
		 * which is a value of any type other than a resource.
		 * @since 5.4.0
		 */
		function jsonSerialize() {
			return $this->data;
		}

		/**
		 * @var array
		 */
		private $data;
	}

	interface IWebService
	{
		function process(Request $request): ServiceResponse;
	}

	interface IAuthorizedService
	{
		function getAuthorized(Request $request): bool;
	}

	interface IAuthenticatedService
	{
		function getAuthenticated(Request $request): bool;
	}

	interface IAuditedService
	{
		function onCall(Request $request);
		function onSuccess(Request $request);
		function onFailed(Request $request);
	}

	/**
	 * Class CacheAwareService
	 * API module for building cache-aware json API services
	 *
	 * @package KrameWork\API
	 * @author docpify <morten@runsafe.no>
	 */
	abstract class CacheAwareService
	{
		/**
		 * @return string Cache-Control header public|private|...
		 */
		protected function getLevel(): string {
			return 'public';
		}

		/**
		 * This method is used to apply authorization requirements on the API
		 * @param $request Request Information about the current API request
		 * @return bool
		 */
		protected function getAuthorized(Request $request): bool {
			return true;
		}

		/**
		 * @return int A unix timestamp for when the data access through this API was last modified.
		 */
		protected abstract function getLastModified(): int;

		/**
		 * Expose the API to the client, processing the request
		 */
		public function expose() {
			$cached = false;
			if (function_exists('apache_request_headers')) {
				$req = apache_request_headers();
				if (isset($req['If-Modified-Since']))
					$cached = strtotime($req['If-Modified-Since']);
			}

			header('Cache-Control: ' . $this->getLevel());

			// Browser sends a request with the method OPTIONS to get the CORS headers above
			if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
				return;

			// Check when the data was modified
			$modified = $this->getLastModified();
			header('X-Modified: ' . serialize($modified));

			if ($modified && $_SERVER['REQUEST_METHOD'] == 'GET') {
				header('Last-Modified: ' . date('r', $modified));
				header('Expires: ' . date('r', $modified + 365 * 24 * 3600));
				if ($cached && $cached >= $modified) {
					header('HTTP/1.1 304 Not Modified');
					return;
				}
			}

			$input = trim(file_get_contents('php://input'));
			$input = $input ? new \ArrayObject(json_decode($input)) : null;

			$path = isset($_SERVER['PATH_INFO']) ? explode('/', $_SERVER['PATH_INFO']) : [];
			while(count($path) && $path[0] == '')
				array_shift($path);

			$request = new Request($_SERVER['REQUEST_METHOD'], $input, $_REQUEST, $path);

			if (!$this->getAuthorized($request)) {
				header('HTTP/1.0 401 Unauthorized');
				return;
			}

			$response = $this->process($request);
			if (headers_sent())
				return;

			header('Content-Type: application/json;charset=UTF-8');
			echo json_encode($response);
		}

		/**
		 * @param $request Request Information about the current API request
		 * @return \ArrayObject
		 */
		public function process(Request $request): ServiceResponse
		{
			$path = $request->getPath();
			if(count($path) == 0 || !in_array($path[0], $this->exported))
				return new \ArrayObject(['success' => false, 'error' => 'Unknown method']);

			$method = $path[0];
			$varargs = count($path) > 1 ? array_slice($path, 1) : [];
			$object = $request->getInputData();
			if ($object !== null)
				$varargs[] = $object;

			try
			{
				$return = $this->filter_call($method, $varargs);
				if($return === null)
				{
					$return = call_user_func_array([$this, $method], $varargs);
					if($return !== false)
						$this->audit_call_success($this->user, $method, $varargs, $return);
					else
						$this->audit_call_failed($this->user, $method, $varargs, null);
				}
			}
			catch(\Exception $e)
			{
				$this->audit_call_failed($this->user, $method, $varargs, $e);
				return new \ArrayObject(['success' => false, 'exception' => $e->getMessage()]);
			}
			return new \ArrayObject(['success' => $return !== false, 'result' => $return === false ? null : $return]);
		}

		/**
		 * Enable authorization and auditing
		 * @param string $endpoint The method that will be invoked
		 * @param string[] $args The arguments to be passed
		 * @return mixed Return null for normal processing, return anything else to abort the call
		 * @throws \Exception
		 */
		public function filter_call($endpoint, $args)
		{
			if(!$this->authorize_call($this->user, $endpoint, $args))
				throw new \Exception('Not authorized');
			$this->audit_call($this->user, $endpoint, $args);
			return null;
		}

		/**
		 * Override this method to implement authorization
		 * @param IDataContainer $user The calling user
		 * @param string $endpoint The method being called
		 * @param string[] $args The arguments given
		 * @return bool Return false to throw an exception blocking the call
		 */
		public function authorize_call($user, $endpoint, $args)
		{
			return true;
		}

		/**
		 * Override this method to implement auditing
		 * @param IDataContainer $user The calling user
		 * @param string $endpoint The method being called
		 * @param string[] $args The arguments given
		 */
		public function audit_call($user, $endpoint, $args)
		{
		}

		/**
		 * Override this method to implement change auditing
		 * @param IDataContainer $user The calling user
		 * @param string $endpoint The method being called
		 * @param string[] $args The arguments given
		 * @param object $old The object prior to change
		 * @param object $new The object as it is now persisted
		 */
		public function audit_change($user, $endpoint, $args, $old, $new)
		{
		}

		/**
		 * Override this method to implement success auditing
		 * @param IDataContainer $user The calling user
		 * @param string $endpoint The method being called
		 * @param string[] $args The arguments given
		 * @param mixed $result The result of the endpoint execution
		 */
		public function audit_call_success($user, $endpoint, $args, $result)
		{
		}

		/**
		 * Override this method to implement failure auditing
		 * @param IDataContainer $user The calling user
		 * @param string $endpoint The method being called
		 * @param string[] $args The arguments given
		 * @param \Exception|null $exception An exception when applicable
		 */
		public function audit_call_failed($user, $endpoint, $args, $exception)
		{
		}

		/**
		 * @var string[]
		 */
		private $exported = [];
	}