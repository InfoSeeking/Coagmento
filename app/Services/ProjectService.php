<?php
namespace App\Services;

use Auth;
use DB;
use Validator;

use App\Models\Membership;
use App\Models\Bookmark;
use App\Models\Project;
use App\Models\User;
use App\Services\MembershipService;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

class ProjectService {
    public function __construct(MembershipService $memberService) {
        $this->user = Auth::user();
        $this->memberService = $memberService;
    }

	public function create($args){
        $validator = Validator::make($args, [
            'title' => 'required',
            ]);
        if ($validator->fails()) {
        	return Status::fromValidator($validator);
        }
        $project = new Project($args);
        $project->creator_id = $this->user->id;
        $project->save();

        $owner = new Membership();
        $owner->user_id = $this->user->id;
        $owner->project_id = $project->id;
        $owner->level = 'o';
        $owner->save();
        return Status::fromResult($project);
	}

    public function get($id) {
        $project = DB::table('memberships')
            ->where('user_id', $this->user->id)
            ->where('project_id', $id)
            ->leftJoin('projects', 'project_id', '=', 'projects.id')
            ->first();
        if (is_null($project)) {
            return Status::fromError('Project not found', StatusCodes::NOT_FOUND);
        }
        return Status::fromResult($project);
    }

	public function getMultiple() {
        $projects = DB::table('memberships')
            ->where('user_id', $this->user->id)
            ->leftJoin('projects', 'project_id', '=', 'projects.id')
            ->get();
		return $projects;
	}

    public function delete($args) {
        $validator = Validator::make($args, [
            'id' => 'required|integer'
            ]);
        if ($validator->fails()) {
            return Status::fromValidator($validator);
        }
        $projectId = $args['id'];
        $project = Project::find($projectId);
        if (is_null($project)) {
            return Status::fromError("Project not found", StatusCodes::NOT_FOUND);
        }
        $memberStatus = $this->memberService->checkPermission($this->user->id, $projectId, 'o');
        if (!$memberStatus->isOK()) {
            return $memberStatus;
        }
        // Delete all project data.
        Membership::where('project_id', $projectId)->delete();
        Bookmark::where('project_id', $projectId)->delete();
        $project->delete();
        return Status::OK();
    }

    public function update($args) {
        $validator = Validator::make($args, [
            'id' => 'required|integer'
            ]);
        if ($validator->fails()) {
            return Status::fromValidator($validator);
        }
        $project = Project::find($args['id']);
        if (is_null($project)) {
            return Status::fromError("Project not found", StatusCodes::NOT_FOUND);
        }
        $project->update($args);
        return Status::OK();
    }

    private $user;
    private $memberService;
}