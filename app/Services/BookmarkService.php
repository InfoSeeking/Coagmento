<?php

namespace App\Services;

use Auth;
use Illuminate\Http\Request;
use Validator;

use App\Models\Bookmark;
use App\Models\Membership;
use App\Models\User;
use App\Utilities\MembershipUtils;
use App\Utilities\Status;
use App\Utilities\StatusWithResult;

class BookmarkService {
	public static function getForUser(Request $req) {
		$user = Auth::user();
		return Bookmark::where('user_id', $user->id)->get();
	}

	public static function create(Request $req) {
		$user = Auth::user();
		$validator = Validator::make($req->all(), [
			'url' => 'required|url',
			'project_id' => 'required|exists:projects,id'
			]);

		if ($validator->fails()) {
			return StatusWithResult::fromValidator($validator);
		}

		$memberStatus = MembershipUtils::checkPermission($user->id, $req->input('project_id'), 'w');
		if (!$memberStatus->isOK()) {
			return StatusWithResult::fromStatus($memberStatus);
		}

		$title = $req->input('title', 'Untitled');
		$bookmark = new Bookmark($req->all());
		$bookmark->user_id = $user->id;
		$bookmark->save();
		return StatusWithResult::fromResult($bookmark);
	}

	public static function delete($id) {
		$user = Auth::user();
		$validator = Validator::make([$id], [
			'id' => 'required|integer|min:0'
			]);
		if ($validator->fails()) {
			return Status::fromValidator($validator);
		}
		$bookmark = Bookmark::find(['user_id' => $user->id, 'id' => $id]);
		$bookmark->delete();
		return Status::OK();
	}
}