<?php

namespace App\Services;

use Auth;
use Validator;

use App\Models\Bookmark;
use App\Models\Membership;
use App\Models\User;
use App\Utilities\MembershipUtils;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class BookmarkService {
	/**
	 * Retrieves a single bookmark.
	 *
	 * @param int $id The bookmark id.
	 * @return Status The resulting bookmark.
	 */
	public static function get($id) {
		$user = Auth::user();
		$bookmark = Bookmark::find($id);
		if (is_null($bookmark)) {
			return Status::fromError('Bookmark not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = MembershipUtils::checkPermission($user->id, $bookmark->project_id, 'r');
		if (!$memberStatus->isOK()) {
			return Status::fromStatus($memberStatus);
		}

		return Status::fromResult($bookmark);
	}

	/**
	 * Retrieves a list of bookmarks.
	 *
	 * If a project is specified, returns all bookmarks from the project.
	 * @param Array $args Can filter by project.
	 * @return Status Collection of bookmarks.
	 */
	public static function getMultiple($args) {
		$user = Auth::user();
		$validator = Validator::make($args, [
			'project_id' => 'sometimes|exists:projects,id'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		$bookmarks = Bookmark::where('user_id', $user->id);
		if (array_key_exists('project_id', $args)) {
			$bookmarks->where('project_id', $args['project_id']);
			$memberStatus = MembershipUtils::checkPermission(
				$user->id, $bookmark->project_id, 'r');

			if (!$memberStatus->isOK()) {
				return Status::fromStatus($memberStatus);
			}
		}
		return Status::fromResult($bookmarks->get());
	}

	/**
	 * Creates a bookmark.
	 * 
	 * @param Array $args Must contain url and project.
	 * @return Status The newly created bookmark.
	 */
	public static function create($args) {
		$user = Auth::user();
		$validator = Validator::make($args, [
			'url' => 'required|url',
			'project_id' => 'required|exists:projects,id'
			]);

		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$projectId = $args['project_id'];
		$memberStatus = MembershipUtils::checkPermission($user->id, $projectId, 'w');
		if (!$memberStatus->isOK()) {
			return Status::fromStatus($memberStatus);
		}

		$title = array_key_exists('title', $args) ? $args['title'] : 'Untitled';
		$bookmark = new Bookmark($args);
		$bookmark->user_id = $user->id;
		$bookmark->project_id = $projectId;
		$bookmark->save();
		return Status::fromResult($bookmark);
	}

	/**
	 * Deletes a bookmark
	 * 
	 * @param int $id The bookmark ID.
	 * @return Status
	 */
	public static function delete($id) {
		$user = Auth::user();
		$validator = Validator::make([$id], [
			'id' => 'required|integer|min:0'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		$bookmark = Bookmark::find($id);
		if (is_null($bookmark)) {
			return Status::fromError('Bookmark not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = MembershipUtils::checkPermission($user->id, $bookmark->project_id, 'w');
		if (!$memberStatus->isOK()) {
			return $memberStatus;
		}
		
		$bookmark->delete();
		return Status::OK();
	}

	/**
	 * Updates a bookmark's fillable fields.
	 * 
	 * @param Array $args
	 * @return Status The updated bookmark.
	 */
	public static function update($args) {
		$user = Auth::user();
		$validator = Validator::make($args, [
			'id' => 'required|integer',
			'url' => 'sometimes|url',
			'move_to' => 'sometimes|integer|exists:projects,id'
			]);

		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}

		$bookmark = Bookmark::find($args['id']);
		if (is_null($bookmark)) {
			return Status::fromError('Bookmark not found', StatusCodes::NOT_FOUND);
		}

		$memberStatus = MembershipUtils::checkPermission($user->id, $bookmark->project_id, 'w');
		if (!$memberStatus->isOK()) {
			return $memberStatus;
		}
		$bookmark->update($args);
		return Status::fromResult($bookmark);
    }

    /**
     * Moves bookmark to another project
     *
     * @param Array $args Must contain project id as input.
     * @return Status The updated bookmark.
     */
    public static function move($args) {
    	$user = Auth::user();
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
		$memberStatus = MembershipUtils::checkPermission($user->id, $bookmark->project_id, 'w');
		if (!$memberStatus->isOK()) {
			return Status::fromError(
				'Insufficient permissions to remove bookmark from current project.');
		}

		$memberStatus = MembershipUtils::checkPermission($user->id, $toProject, 'w');
		if (!$memberStatus->isOK()) {
			return Status::fromError(
				'Insufficient permissions to move bookmark to new project.');
		}

		$bookmark->project_id = $toProject;
		$bookmark->save();
		return Status::fromResult($bookmark);
    }

    // TODO.
    public static function moveMultiple($args) {}
}