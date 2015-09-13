<?php

namespace App\Services;

use App\Models\Bookmark;
use App\Models\User;
use App\Utilities\Status;
use App\Utilities\StatusWithResult;
use Validator;

class BookmarkService {
	public static function getAllForUser(User $user) {
		return Bookmark::where('user_id', $user->id)->get();
	}

	public static function insert(User $user, $request) {
		$validator = Validator::make($request->all(), [
			'url' => 'required|url'
			]);
		if ($validator->fails()) {
			return StatusWithResult::fromValidator($validator);
		}
		$title = $request->input('title', 'Untitled');
		$bookmark = new Bookmark($request->all());
		$bookmark->user_id = $user->id;
		$bookmark->save();
		return StatusWithResult::fromResult($bookmark);
	}

	public static function delete(User $user, $id) {
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