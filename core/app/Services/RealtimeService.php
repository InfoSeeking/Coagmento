<?php
namespace App\Services;

use Auth;
use Log;

class RealtimeService {
	public function __construct() {
		$this->url = env('REALTIME_SERVER') . '/publish';
		$this->user = Auth::user();
	}

	public function create($projectId, $item) {
		$this->emit([
			'data' => [$item->jsonSerialize()],
			'action' => 'create',
			'projectID' => $projectId,
			'userID' => $this->user->id
			]);
	}

	public function delete($projectId, $item) {}

	public function deleteMultiple($projectId, $items) {}

	public function update($projectId, $items) {}

	public function login() {}

	public function logout() {}

	/**
	 * @param {array} $data Must be JSON serializable.
	 */
	private function emit($data) {
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data),
		    ),
		);
		$context  = stream_context_create($options);
		file_get_contents($this->url, false, $context);
	}
}