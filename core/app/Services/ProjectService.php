<?php
namespace App\Services;

use Auth;
use DB;
use Validator;

use App\Models\Membership;
use App\Models\Bookmark;
use App\Models\Project;
use App\Models\Snippet;
use App\Models\Tag;
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
            'private' => 'sometimes|boolean'
            ]);
        if ($validator->fails()) {
        	return Status::fromValidator($validator);
        }
        $project = new Project($args);
        $project->creator_id = $this->user->id;
        $project->private = array_key_exists('private', $args) ? $args['private'] : false;
        $project->save();

        $owner = new Membership();
        $owner->user_id = $this->user->id;
        $owner->project_id = $project->id;
        $owner->level = 'o';
        $owner->save();
        return Status::fromResult($project);
	}

    public function get($id) {
        $project = Project::find($id);
        if (is_null($project)) {
            return Status::fromError('Project not found', StatusCodes::NOT_FOUND);
        }
        $memberStatus = $this->memberService->checkPermission($project->id, 'r', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;
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
        $memberStatus = $this->memberService->checkPermission($project->id, 'o', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;
        // Delete all project data.
        Membership::where('project_id', $projectId)->delete();
        Bookmark::where('project_id', $projectId)->delete();
        Tag::where('project_id', $projectId)->delete();
        Snippet::where('project_id', $projectId)->delete();
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
        $memberStatus = $this->memberService->checkPermission($project->id, 'w', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;
        $project->update($args);
        return Status::OK();
    }
}