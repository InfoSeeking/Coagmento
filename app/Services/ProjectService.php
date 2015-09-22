<?php
namespace App\Services;

use Auth;
use Illuminate\Http\Request;
use Validator;

use App\Models\Project;
use App\Models\User;
use App\Utilities\StatusWithResult;
use App\Services\BookmarkService;

class ProjectService {
	public static function create(Request $req){
		$user = Auth::user();
        $validator = Validator::make($req->all(), [
            'title' => 'required',
            ]);
        if ($validator->fails()) {
        	return StatusWithResult::fromValidator($validator);
        }
        $project = new Project($req->all());
        $project->save();
        $project->creator_id = $user->id;
        return StatusWithResult::fromResult($project);
	}

	public static function addBookmark(Request $req) {
		$user = Auth::user();
		$validator = Validator::make($req->all(), [
			'project_id' => 'required|exists:projects,id'
			]);
		if ($validator->fails()) {
        	return StatusWithResult::fromValidator($validator);
        }
        $project_id = $req->input('project_id');
		// TODO: Check if user has correct permissions to create a bookmark.
		$bookmarkStatus = BookmarkService::create($req, $project_id);
		return $bookmarkStatus;
	}
}