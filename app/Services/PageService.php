<?php

namespace App\Services;

use Auth;
use Validator;

use App\Models\Page;
use App\Services\MembershipService;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class PageService {
	public function __construct(MembershipService $memberService) {
		$this->memberService = $memberService;
		$this->user = Auth::user();
	}

	private $user;
	private $memberService;

	public function get($id) {
		$page = Page::find($id);
		if (is_null($page)) {
			return Status::fromError('Page not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = $this->memberService->checkPermission($this->user->id, $page->project_id, 'r');
		if (!$memberStatus->isOK()) {
			return Status::fromStatus($memberStatus);
		}

		return Status::fromResult($page);
	}
	public function getMultiple($args) {
		$validator = Validator::make($args, [
			'project_id' => 'sometimes|exists:projects,id'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		if (array_key_exists('project_id', $args)) {
			$memberStatus = $this->memberService->checkPermission(
				$this->user->id, $args['project_id'], 'r');

			if (!$memberStatus->isOK()) {
				return Status::fromStatus($memberStatus);
			}

			$pages = Page::where('project_id', $args['project_id']);
			return Status::fromResult($pages->get());
		}

		// Return all user created pages.
		$pages = Page::where('user_id', $this->user->id);
		return Status::fromResult($pages->get());
	}

	public function create($args) {
		$validator = Validator::make($args, [
			'url' => 'required|url',
			'project_id' => 'required|exists:projects,id',
			]);
		// TODO: check if this is a query page, if so, save a new query as well.
		
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$projectId = $args['project_id'];
		$memberStatus = $this->memberService->checkPermission($this->user->id, $projectId, 'w');
		if (!$memberStatus->isOK()) {
			return $memberStatus;
		}

		$title = array_key_exists('title', $args) ? $args['title'] : 'Untitled';

		$page = new Page($args);
		$page->user_id = $this->user->id;
		$page->project_id = $projectId;
		$page->save();

		return Status::fromResult($page);
	}

	public function delete($id) {
		$validator = Validator::make([$id], [
			'id' => 'required|integer'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		$page = Page::find($id);
		if (is_null($page)) {
			return Status::fromError('Page not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = $this->memberService->checkPermission($this->user->id, $page->project_id, 'w');
		if (!$memberStatus->isOK()) {
			return $memberStatus;
		}
		
		$page->delete();
		return Status::OK();
	}
}	