<?php

namespace App\Services;

use Auth;
use Validator;

use App\Models\Page;
use App\Services\MembershipService;
use App\Services\QueryService;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class PageService {
	public function __construct(
		MembershipService $memberService,
		QueryService $queryService) {
		$this->memberService = $memberService;
		$this->queryService = $queryService;
		$this->user = Auth::user();
	}

	private $user;
	private $memberService;

	public function get($id) {
		$page = Page::with('thumbnail')->find($id);
		if (is_null($page)) {
			return Status::fromError('Page not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = $this->memberService->checkPermission($page->project_id, 'r', $this->user);
		if (!$memberStatus->isOK()) return Status::fromStatus($memberStatus);

		return Status::fromResult($page);
	}

	public function getMultiple($args, $countOnly=false) {
		$validator = Validator::make($args, [
			'project_id' => 'sometimes|exists:projects,id',
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		if (array_key_exists('project_id', $args)) {
			$memberStatus = $this->memberService->checkPermission(
				$args['project_id'], 'r', $this->user);
			if (!$memberStatus->isOK()) return Status::fromStatus($memberStatus);

			$pages = Page::with('thumbnail')->where('project_id', $args['project_id']);
			if ($countOnly) return Status::fromResult($pages->count());
			return Status::fromResult($pages->get());
		}

		// Return all user created pages.
		if (!$this->user) return Status::fromError('Log in to see pages or specify a project_id');
		$pages = Page::with('thumbnail')->where('user_id', $this->user->id);
		if ($countOnly) return Status::fromResult($pages->count());
		return Status::fromResult($pages->get());
	}

	public function create($args) {
		$validator = Validator::make($args, [
			'url' => 'required|url',
			'project_id' => 'required|exists:projects,id',
			'if_query' => 'sometimes|in:both,page_only,query_only'
			]);	
		
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		$projectId = $args['project_id'];
		$memberStatus = $this->memberService->checkPermission($projectId, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$title = array_key_exists('title', $args) ? $args['title'] : 'Untitled';

		$save = array_key_exists('if_query', $args) ? $args['if_query'] : 'both';

		// Because we can store a query, page, or both, we'll return an array.
		$results = [];

		if ($save == 'query_only' || $save == 'both') {
			// Check if this url is a search engine query, and save if it is.
			$queryStatus = $this->queryService->parseQuery($args['url']);
			if ($queryStatus->isOK()) {
				// Merge query parameters with args to include project_id.
				$queryArgs = array_merge($args, $queryStatus->getResult());
				$queryStatus = $this->queryService->create($queryArgs);
				if (!$queryStatus->isOK()) {
					return $queryStatus;
				}
				$results['query'] = $queryStatus->getResult();
			}
		}

		if ($save == 'page_only' || $save == 'both') {
			$page = new Page($args);
			$page->user_id = $this->user->id;
			$page->project_id = $projectId;
			$page->load('thumbnail');
			$page->save();
			$results['page'] = $page;
		}

		return Status::fromResult($results);
	}

	public function delete($id) {
		$page = Page::find($id);
		if (is_null($page)) {
			return Status::fromError('Page not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = $this->memberService->checkPermission($page->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;
		
		$page->delete();
		return Status::OK();
	}
}	