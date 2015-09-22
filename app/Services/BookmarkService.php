<?php

namespace App\Services;

use Auth;
use Illuminate\Http\Request;
use Validator;

use App\Models\Bookmark;
use App\Models\User;
use App\Utilities\Status;
use App\Utilities\StatusWithResult;

class BookmarkService {
	public static function getForUser() {
		$user = Auth::user();
		return Bookmark::where('user_id', $user->id)->get();
	}

	public static function create(Request $req, $project_id) {
		$user = Auth::user();
		$validator = Validator::make($req->all(), [
			'url' => 'required|url',
			]);
		if ($validator->fails()) {
			return StatusWithResult::fromValidator($validator);
		}
		$title = $req->input('title', 'Untitled');
		$bookmark = new Bookmark($req->all());
		$bookmark->user_id = $user->id;
		$bookmark->project_id = $project_id;
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