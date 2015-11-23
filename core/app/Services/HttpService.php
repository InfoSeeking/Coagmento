<?php
namespace App\Services;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class HttpService {
	public function __construct() {
		$this->clear();
	}

	public function withMethod($method) {
		$this->method = $method;
		return $this;
	}

	public function withFormData($data) {
		$this->encoded_data = http_build_query($data);
		$this->contentType = 'application/x-www-form-urlencoded';
		return $this;
	}

	public function withJson($data) {
		$this->encoded_data = json_encode($data);
		$this->contentType = 'application/json';
		return $this;
	}

	public function send($url) {
		// Use key 'http' even if you send the request to https://...
		$options = [
		    'http' => [
		        'header'  => sprintf("Content-type: %s\r\n", $this->contentType),
		        'method'  => $this->method,
		        'content' => $this->encoded_data,
		    ],
		];
		$context  = stream_context_create($options);
		$returnData = false;
		try {
			$returnData = file_get_contents($url, false, $context);
		} catch (Exception $e) {
			return Status::fromError('Server at ' . $url . ' unreachable', StatusCodes::SERVER_DOWN);
		}
		$this->clear();
		return Status::fromResult($returnData);
	}

	private function clear() {
		$this->method = 'GET';
		$this->encoded_data = null;
		$this->contentType = null;
	}
}