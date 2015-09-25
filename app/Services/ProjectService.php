<?php
namespace App\Services;

use Auth;
use DB;
use Validator;

use App\Models\Membership;
use App\Models\Bookmark;
use App\Models\Project;
use App\Models\User;
use App\Utilities\MembershipUtils;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class ProjectService {
	public static function create($args){
		$user = Auth::user();
        $validator = Validator::make($args, [
            'title' => 'required',
            ]);
        if ($validator->fails()) {
        	return Status::fromValidator($validator);
        }
        $project = new Project($args);
        $project->creator_id = $user->id;
        $project->save();

        $owner = new Membership();
        $owner->user_id = $user->id;
        $owner->project_id = $project->id;
        $owner->level = 'o';
        $owner->save();
        return Status::fromResult($project);
	}

    public static function get($id) {
        $user = Auth::user();
        $project = DB::table('memberships')
            ->where('user_id', $user->id)
            ->where('project_id', $id)
            ->leftJoin('projects', 'project_id', '=', 'projects.id')
            ->first();
        if (is_null($project)) {
            return Status::fromError('Project not found');
        }
        return Status::fromResult($project);
    }

	public static function getMultiple() {
		$user = Auth::user();
        $projects = DB::table('memberships')
            ->where('user_id', $user->id)
            ->leftJoin('projects', 'project_id', '=', 'projects.id')
            ->get();
		return $projects;
	}

    public static function delete($args) {
        $user = Auth::user();
        $validator = Validator::make($args, [
            'id' => 'required|integer|exists:projects,id'
            ]);
        if ($validator->fails()) {
            return Status::fromValidator($validator);
        }
        $projectId = $args['id'];
        $project = Project::where('id', $projectId);
        $memberStatus = MembershipUtils::checkPermission($user->id, $projectId, 'o');
        if (!$memberStatus->isOK()) {
            return $memberStatus;
        }
        Membership::where('project_id', $projectId)->delete();
        Bookmark::where('project_id', $projectId)->delete();
        $project->delete();
        return Status::OK();
    }

    // TODO.
    public static function update($args) {

    }
}