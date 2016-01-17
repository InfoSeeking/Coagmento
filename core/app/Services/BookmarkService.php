<?php

namespace App\Services;

use Auth;
use Validator;

use App\Models\Bookmark;
use App\Models\BookmarksAndTags;
use App\Models\Membership;
use App\Models\User;
use App\Services\MembershipService;
use App\Services\TagService;
use App\Services\RealtimeService;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class BookmarkService {
	public function __construct(
		TagService $tagService,
		MembershipService $memberService,
		RealtimeService $realtimeService
		) {
		$this->tagService = $tagService;
		$this->memberService = $memberService;
		$this->realtimeService = $realtimeService;
		$this->user = Auth::user();
	}
	
	/**
	 * Retrieves a single bookmark.
	 *
	 * @param int $id The bookmark id.
	 * @return Status The resulting bookmark.
	 */
	public function get($id) {
		$bookmark = Bookmark::find($id);
		if (is_null($bookmark)) {
			return Status::fromError('Bookmark not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = $this->memberService->checkPermission($bookmark->project_id, 'r', $this->user);
		if (!$memberStatus->isOK()) return Status::fromStatus($memberStatus);

		return Status::fromResult($bookmark);
	}

	/**
	 * Retrieves a list of bookmarks.
	 *
	 * If a project is specified, returns all bookmarks from the project.
	 * Otherwise returns only bookmarks created by this user.
	 * @param Array $args Can filter by project.
	 * @return Status Collection of bookmarks.
	 */
	public function getMultiple($args) {
		$validator = Validator::make($args, [
			'project_id' => 'sometimes|exists:projects,id'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		if (array_key_exists('project_id', $args)) {
			$memberStatus = $this->memberService->checkPermission($args['project_id'], 'r', $this->user);
			if (!$memberStatus->isOK()) return Status::fromStatus($memberStatus);
			$bookmarks = Bookmark::with('thumbnail')->where('project_id', $args['project_id']);
			return Status::fromResult($bookmarks->get());
		}

		// Return all user created bookmarks.
		$bookmarks = Bookmark::with('thumbnail')->where('user_id', $this->user->id);
		return Status::fromResult($bookmarks->get());
	}

	/**
	 * Creates a bookmark.
	 * 
	 * @param Array $args Must contain url and project.
	 * @return Status The newly created bookmark.
	 */
	public function create($args) {
		$validator = Validator::make($args, [
			'url' => 'required|url',
			'project_id' => 'required|exists:projects,id',
			'notes' => 'sometimes|string',
			'tags' => 'sometimes|array'
			]);

		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$memberStatus = $this->memberService->checkPermission($args['project_id'], 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		$bookmark = new Bookmark($args);
		$bookmark->user_id = $this->user->id;
		$bookmark->title = array_key_exists('title', $args) ? $args['title'] : 'Untitled';
		$bookmark->project_id = $args['project_id'];
		$bookmark->load('thumbnail');
		$bookmark->save();

		if (array_key_exists('tags', $args)) {
			$tagStatus = $this->updateTags($bookmark, $args['tags']);
			if (!$tagStatus->isOK()) {
				return $tagStatus;
			}
		}

		$this->realtimeService
			->withModel($bookmark)
			->onProject($bookmark->project_id)
			->emit('create');

		return Status::fromResult($bookmark);
	}

	/**
	 * Deletes a bookmark
	 * 
	 * @param int $id The bookmark ID.
	 * @return Status
	 */
	public function delete($id) {
		$bookmark = Bookmark::find($id);
		if (is_null($bookmark)) {
			return Status::fromError('Bookmark not found', StatusCodes::NOT_FOUND);
		}
		
		$memberStatus = $this->memberService->checkPermission($bookmark->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;
		
		$this->realtimeService
			->withModel($bookmark)
			->onProject($bookmark->project_id)
			->emit('delete');
		$bookmark->delete();
		return Status::OK();
	}

	/**
	 * Updates a bookmark's fillable fields.
	 * 
	 * @param Array $args
	 * @return Status The updated bookmark.
	 */
	public function update($args) {
		$validator = Validator::make($args, [
			'id' => 'required|integer',
			'url' => 'sometimes|url',
			'tags' => 'sometimes|array'
			]);

		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		$bookmark = Bookmark::find($args['id']);
		if (is_null($bookmark)) {
			return Status::fromError('Bookmark not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = $this->memberService->checkPermission($bookmark->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) return $memberStatus;

		if (array_key_exists('tags', $args)) {
			$tagStatus = $this->updateTags($bookmark, $args['tags']);
			if (!$tagStatus->isOK()) {
				return $tagStatus;
			}
		}
		$bookmark->update($args);

		$this->realtimeService
			->withModel($bookmark)
			->onProject($bookmark->project_id)
			->emit('update');
		return Status::fromResult($bookmark);
    }

    /**
     * Moves bookmark to another project
     *
     * @param Array $args Must contain project id as input.
     * @return Status The updated bookmark.
     */
    public function move($args) {
    	$validator = Validator::make($args, [
    		'project_id' => 'required|integer|exists:projects,id',
    		'id' => 'required|integer'
    		]);
    	if ($validator->fails()) {
    		return Status::fromValidator($validator);
    	}

    	$toProject = $args['project_id'];
    	$bookmark = Bookmark::find($args['id']);
		if (is_null($bookmark)) {
			return Status::fromError('Bookmark not found', StatusCodes::NOT_FOUND);
		}

		// User needs to have write permission on both the 'from' and 'to' projects.
		$memberStatus = $this->memberService->checkPermission($bookmark->project_id, 'w', $this->user);
		if (!$memberStatus->isOK()) {
			return Status::fromError(
				'Insufficient permissions to remove bookmark from current project.');
		}

		$memberStatus = $this->memberService->checkPermission($toProject, 'w', $this->user);
		if (!$memberStatus->isOK()) {
			return Status::fromError(
				'Insufficient permissions to move bookmark to new project.');
		}
		$fromProject = $bookmark->project_id;

		$bookmark->project_id = $toProject;
		$bookmark->save();

		$this->realtimeService
			->withModel($bookmark)
			->onProject($fromProject)
			->withContext('move_from')
			->emit('update');

		$this->realtimeService
			->withModel($bookmark)
			->onProject($toProject)
			->withContext('move_to')
			->emit('update');
		return Status::fromResult($bookmark);
    }

    // TODO.
    public function moveMultiple($args) {}

    private function updateTags(Bookmark $bookmark, $tagList) {
		$tagStatus = $this->tagService->getMultipleOrCreate([
			'project_id' => $bookmark->project_id,
			'name_list' => $tagList
			]);
		if (!$tagStatus->isOK()) {
			return $tagStatus;
		}
		$serializedTags = [];
		foreach ($tagStatus->getResult() as $tag) {
			$relation = new BookmarksAndTags();
			$relation->bookmark_id = $bookmark->id;
			$relation->tag_id = $tag->id;
			$relation->save();
			array_push($serializedTags, $tag);
		}

		return Status::OK();
    }
}