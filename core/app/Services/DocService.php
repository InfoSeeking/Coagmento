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
	protected $sessionTime; 

	public function __construct(MembershipService $memberService) {
		$this->sessionTime = 60*60*24*30; // Time of session in seconds (thirty days).
		$this->memberService = $memberService;
		$this->active = !!env('ETHERPAD_SERVER');
		$this->url = env('ETHERPAD_SERVER');
		$this->domain = env('DOMAIN') ? env('DOMAIN') : '';
		$this->user = Auth::user();
		if ($this->active) {
			$this->client = new \EtherpadLite\Client(env('ETHERPAD_APIKEY'), $this->url);
		}
	}

	public function create($args) {
		if (!$this->active) return Status::fromError('Documents are disabled');
		
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

		try {
			// Create a group from the project id.
			$response = $this->client->createGroupIfNotExistsFor($projectId);
			if ($response->getCode() != \EtherpadLite\Response::CODE_OK) {
				DB::rollback();
				return Status::fromError('Could not create document');
			}
	        $groupId = $response->getData()['groupID'];

			// Create a pad from the document id.
			$padId = '' . $doc->id;
			$response = $this->client->createGroupPad($groupId, $padId);
			$response = $this->client->createPad($padId, '');
			if ($response->getCode() != \EtherpadLite\Response::CODE_OK) {
				DB::rollback();
				return Status::fromError('Could not create document');
			}

			$doc->etherpad_group_id = $groupId;
			$doc->project_id = $args['project_id'];
			$doc->creator_id = $this->user->id;
			$doc->save();
			DB::commit();
			return Status::fromResult($doc);
		} catch (\Exception $e) {
			return Status::fromError('Cannot connect to document service');
		}
	}

	public function getWithSession($args) {
		if (!$this->active) return Status::fromError('Documents are disabled');
		
		$validator = Validator::make($args, [
			'doc_id' => 'required|integer'
			]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$doc = Doc::find($args['doc_id']);
		if (is_null($doc)) return Status::fromError('Document not found', StatusCodes::NOT_FOUND);

		$memberStatus = $this->memberService->checkPermission($doc->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		try {
			$response = $this->client->createAuthorIfNotExistsFor($this->user->id, $this->user->name);
			if ($response->getCode() != \EtherpadLite\Response::CODE_OK) {
				return Status::fromError('Could not create document user');
			}
			$authorId = $response->getData()['authorID'];

	        $response = $this->client->createSession(
	        	$doc->etherpad_group_id,
	        	$authorId,
	        	time() + $this->sessionTime);

	        if ($response->getCode() !== \EtherpadLite\Response::CODE_OK) {
	        	return Status::fromError('Could not create document session');
	        }
	        $sessionID = $response->getData()['sessionID'];

			setcookie('sessionID', $sessionID, 0, '/', $this->domain);
			return Status::fromResult($doc);
		} catch (\Exception $e) {
			return Status::fromError('Cannot connect to document service');
		}
	}

	public function get($docId) {
		if (!$this->active) return Status::fromError('Documents are disabled');
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

	public function getMultiple($args, $countOnly=false) {
		$validator = Validator::make($args, [
			'project_id' => 'sometimes|exists:projects,id'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		if (array_key_exists('project_id', $args)) {
			$memberStatus = $this->memberService->checkPermission($args['project_id'], 'r', $this->user);
			if (!$memberStatus->isOK()) return Status::fromStatus($memberStatus);
			$docs = Doc::where('project_id', $args['project_id']);
			if ($countOnly) return Status::fromResult($docs->count());
			return Status::fromResult($docs->get());
		}

		// Return all user created docs.
		if (!$this->user) return Status::fromError('Log in to see docs or specify a project_id');
		$docs = Doc::where('user_id', $this->user->id);
		if ($countOnly) return Status::fromResult($docs->count());
		return Status::fromResult($docs->get());
	}

	public function delete($docId) {
		if (!$this->active) return Status::fromError('Documents are disabled');
		
		$validator = Validator::make(['doc_id' => $docId], [
			'doc_id' => 'required|integer'
			]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$doc = Doc::find($docId);
		if (is_null($doc)) return Status::fromError('Document not found', StatusCodes::NOT_FOUND);

		$memberStatus = $this->memberService->checkPermission($doc->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		try {
			$padId = $this->getPadId($doc);
	        $response = $this->client->deletePad($padId);
	        if ($response->getCode() != \EtherpadLite\Response::CODE_OK) {
	        	return Status::fromError('Could not delete document');
	        }

	        $doc->delete();
			return Status::OK();
		} catch (\Exception $e) {
			return Status::fromError('Cannot connect to document service');
		}
	}

	public function getPadId($doc) {
		return $doc->etherpad_group_id . '$' . $doc->id;
	}

	public function getText($args) {
		if (!$this->active) return Status::fromError('Documents are disabled');

		$validator = Validator::make($args, [
				'id' => 'required|integer',
				'as_html' => 'sometimes|boolean'
				]);
		if ($validator->fails()) return Status::fromValidator($validator);

		$doc = Doc::find($args['id']);
		if (is_null($doc)) return Status::fromError('Document not found', StatusCodes::NOT_FOUND);

		$memberStatus = $this->memberService->checkPermission($doc->project_id, 'r', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		try {
			$padId = $this->getPadId($doc);
			$asHtml = false;
			if (array_key_exists('as_html', $args)) $asHtml = $args['as_html'];

			if ($asHtml) {
				$response = $this->client->getHTML($padId);
			} else {
				$response = $this->client->getText($padId);
			}
			
			if ($response->getCode() != \EtherpadLite\Response::CODE_OK) {
				return Status::fromError('Could not get document text');
			}
			return Status::fromResult(['text' => $response->getData()]);
		} catch (\Exception $e) {
			return Status::fromError('Cannot connect to document service');
		}
	}
}