<?php

namespace App\Services;

use App\Models\Bookmark;
use App\Models\User;
use App\Utilities\Status;

class BookmarkService {
	public static function getAllForUser(User $user) {
		return Bookmark::find(['user_id' => $user->id]);
	}

	public static function create($input) {

	}
}