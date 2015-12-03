<?php
namespace App\Services;

use Auth;
use DB;
use Validator;
use App\Models\Doc;
use App\Services\MembershipService;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class DocService {
	protected $sessionTime = 60*60*24*30; // Time of session in seconds (thirty days).

	public function __construct(MembershipService $memberService) {
		$this->memberService = $memberService;
		$this->active = !!env('ETHERPAD_SERVER');
		$this->url = env('ETHERPAD_SERVER');
		$this->client = new \EtherpadLite\Client(env('ETHERPAD_APIKEY'), $this->url);
		$this->user = Auth::user();
	}

	public function create($args) {
		$validator = Validator::make($args, [
			'title' => 'required|string',
			'project_id' => 'required|exists:projects,id'
			]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$projectId = $args['project_id'];
		$memberStatus = $this->memberService->checkPermission($projectId, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		// Begin a transaction in case there are errors with Etherpad.
		DB::beginTransaction();
		$doc = new Doc($args);
		$doc->save();

		// Create a group from the project id.
		$response = $this->client->createGroupIfNotExistsFor($projectId);
		if ($response->getCode() != 0) {
			DB::rollback();
			return Status::fromError('Could not create document');
		}
        $groupId = $response->getData()['groupID'];

		// Create a pad from the document id.
		$padId = '' . $doc->id;
		$response = $this->client->createGroupPad($groupId, $padId);
		$response = $this->client->createPad($padId, '');
		if ($response->getCode() != 0) {
			DB::rollback();
			return Status::fromError('Could not create document');
		}

		$doc->etherpad_group_id = $groupId;
		$doc->project_id = $args['project_id'];
		$doc->creator_id = $this->user->id;
		$doc->save();
		DB::commit();
		return Status::fromResult($doc);
	}

	public function getWithSession($args) {
		$validator = Validator::make($args, [
			'doc_id' => 'required|integer'
			]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$doc = Doc::find($args['doc_id']);
		if (is_null($doc)) return Status::fromError('Document not found', StatusCodes::NOT_FOUND);

		$memberStatus = $this->memberService->checkPermission($doc->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$response = $this->client->createAuthorIfNotExistsFor($this->user->id, $this->user->name);
		if ($response->getCode() != 0) {
			return Status::fromError('Could not create document user');
		}
		$authorId = $response->getData()['authorID'];

        $response = $this->client->createSession(
        	$doc->etherpad_group_id,
        	$authorId,
        	time() + $this->sessionTime);

        if ($response->getCode() !== 0) {
        	return Status::fromError('Could not create document session');
        }
        $sessionID = $response->getData()['sessionID'];

		setcookie('sessionID', $sessionID);
		return Status::fromResult($doc);
	}

	public function get($docId) {
		$validator = Validator::make($args, [
			'doc_id' => 'required|integer'
			]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$doc = Doc::find($args['doc_id']);
		if (is_null($doc)) return Status::fromError('Document not found', StatusCodes::NOT_FOUND);

		$memberStatus = $this->memberService->checkPermission($doc->project_id, 'r', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		return Status::fromResult($doc);
	}

	public function delete($docId) {
		$validator = Validator::make($args, [
			'doc_id' => 'required|integer'
			]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$doc = Doc::find($args['doc_id']);
		if (is_null($doc)) return Status::fromError('Document not found', StatusCodes::NOT_FOUND);

		$memberStatus = $this->memberService->checkPermission($doc->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$padId = $this->getPadId($doc);
        $response = $this->client->deletePad($padId);
        if ($response->getCode() != 0) {
        	return Status::fromError('Could not delete document');
        }

        $doc->delete();
		return Status::OK();
	}

	public function getPadId($doc) {
		return $doc->etherpad_group_id . '$' . $doc->id;
	}
}