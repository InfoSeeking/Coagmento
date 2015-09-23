<?php
namespace App\Services;

use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

use App\Models\Membership;
use App\Models\Bookmark;
use App\Models\Project;
use App\Models\User;
use App\Utilities\MembershipUtils;
use App\Utilities\Status;
use App\Utilities\StatusCodes;
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
        $project->creator_id = $user->id;
        $project->save();

        $owner = new Membership();
        $owner->user_id = $user->id;
        $owner->project_id = $project->id;
        $owner->level = 'o';
        $owner->save();
        return StatusWithResult::fromResult($project);
	}

    public static function get($id) {
        $user = Auth::user();
        $project = DB::table('memberships')
            ->where('user_id', $user->id)
            ->where('project_id', $id)
            ->leftJoin('projects', 'project_id', '=', 'projects.id')
            ->first();
        if (is_null($project)) {
            return StatusWithResult::fromError('Project not found');
        }
        return StatusWithResult::fromResult($project);
    }

	public static function getMultiple() {
		$user = Auth::user();
        $projects = DB::table('memberships')
            ->where('user_id', $user->id)
            ->leftJoin('projects', 'project_id', '=', 'projects.id')
            ->get();
		return $projects;
	}

    public static function delete(Request $req, $id) {
        $user = Auth::user();
        $project = Project::where('id', $id);
        if ($project->count() == 0) {
            return Status::fromError('Project does not exist', StatusCodes::NOT_FOUND);
        }
        $memberStatus = MembershipUtils::checkPermission($user->id, $id, 'o');
        if (!$memberStatus->isOK()) {
            return $memberStatus;
        }
        Membership::where('project_id', $id)->delete();
        Bookmark::where('project_id', $id)->delete();
        $project->delete();
        return Status::OK();
    }

    // TODO.
    public static function update(Request $req) {

    }
}