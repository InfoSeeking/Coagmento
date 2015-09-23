<?php
namespace App\Services;

use Auth;
use Illuminate\Http\Request;
use Validator;

use App\Models\Membership;
use App\Models\Project;
use App\Models\User;
use App\Utilities\StatusWithResult;

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

        $owner = new Membership();
        $owner->user_id = $user->id;
        $owner->project_id = $project->id;
        $owner->level = 'o';
        $owner->save();
        return StatusWithResult::fromResult($project);
	}

	public static function getForUser(Request $req) {
		$user = Auth::user();
		return Project::where('user_id', $user->id)->get();
	}
}