<?php

namespace App\Services;

use Auth;
use Validator;
use App\Models\Snippet;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class SnippetService {
	public function __construct(MembershipService $memberService){
		$this->user = Auth::user();
		$this->memberService = $memberService;
	}

	public function get($id) {
		$snippet = Snippet::find($id);
		if (is_null($snippet)) {
			return Status::fromError('Snippet not found', StatusCodes::NOT_FOUND);
		}
		$memberStatus = $this->memberService->checkPermission($snippet->project_id, 'r', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		return Status::fromResult($snippet);
	}

	public function getMultiple($args){
		$validator = Validator::make($args, [
			'project_id' => 'sometimes|exists:projects,id'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		if (array_key_exists('project_id', $args)) {
			$memberStatus = $this->memberService->checkPermission(
				$args['project_id'], 'r', $this->user);
			if (!$memberStatus->isOK()) return Status::fromStatus($memberStatus);

			$snippets = Snippet::where('project_id', $args['project_id']);
			return Status::fromResult($snippets->get());
		}

		// Return all user created snippets.
		$snippets = Bookmark::where('user_id', $this->user->id);
		return Status::fromResult($snippets->get());
	}

	public function create($args) {
		$validator = Validator::make($args, [
			'text' => 'required|string',
			'url' => 'required|string|url',
			'project_id' => 'required|integer|exists:projects,id'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		$memberStatus = $this->memberService->checkPermission($args['project_id'], 'r', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$snippet = new Snippet($args);
		$snippet->user_id = $this->user->id;
		$snippet->project_id = $args['project_id'];
		$snippet->save();
		return Status::fromResult($snippet);
	}

	public function delete($id) {
		$snippet = Snippet::find($id);
		if (is_null($snippet)) {
			return Status::fromError('Snippet not found', StatusCodes::NOT_FOUND);
		}
		$memberStatus = $this->memberService->checkPermission($snippet->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$snippet->delete();
		return Status::OK();
	}

	public function update($args) {
		$validator = Validator::make($args, [
			'text' => 'sometimes|string',
			'url' => 'sometimes|string|url',
			'id' => 'required|integer'
			]);
		$snippet = Snippet::find($args['id']);
		if (is_null($snippet)) {
			return Status::fromError('Snippet not found', StatusCodes::NOT_FOUND);
		}
		$memberStatus = $this->memberService->checkPermission($snippet->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$snippet->update($args);
		return Status::fromResult($snippet);
	}
}