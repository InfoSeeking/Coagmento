<?php
namespace App\Services;

use Auth;
use Log;

// Problems:
// This depends on $item being a Model. What if we only update tags? What if we want to include additional data?
// It should be easy to only send the diff.
// Also, the client needs to know about the data type to uniquely identify.
// We might need to know more about the client before clear decisions can be made.

// Item type: "bookmark", etc. 
// Emission type: "create", "update", "delete"
class RealtimeService {
	public function __construct() {
		$this->active = !!env('REALTIME_SERVER');
		$this->url = env('REALTIME_SERVER') . '/publish';
		$this->user = Auth::user();
		$this->clear();
	}

	public function withModel($model) {
		$this->data = [$model->jsonSerialize()];
		$this->dataType = $model->getTable();
		return $this;
	}

	public function withModels($models, $type=null) {
		$this->data = [];
		if (count($models) == 0) return;
		$this->dataType = $models[0]->getTable();
		foreach ($models as $model) {
			array_push($this->data, $model->jsonSerialize());
		}
		return $this;
	}

	public function withRawData($data, $type="") {
		$this->data = $data;
		$this->dataType = $type;
		return $this;
	}

	public function onProject($projectId) {
		$this->projectId = $projectId;
		return $this;
	}

	public function withContext($context) {
		$this->context = $context;
		return $this;
	}

	private function clear() {
		$this->data = null;
		$this->projectId = null;
		$this->context = null;
		return $this;
	}

	/**
	 * @param {array} $data Must be JSON serializable.
	 */
	public function emit($action) {
		if (!$this->active) return;
		$json = [
			'data' => $this->data,
			'dataType' => $this->dataType,
			'action' => $action,
			'context' => $this->context,
			'projectID' => $this->projectId,
			'userID' => $this->user->id,
			];
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($json),
		    ),
		);
		$context  = stream_context_create($options);
		file_get_contents($this->url, false, $context);
		$this->clear();
	}
}