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
            return Status::fromError('Project not found?', StatusCodes::NOT_FOUND);
        }
        $memberStatus = $this->memberService->checkPermission($project->id, 'r', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;
        return Status::fromResult($project);
    }

	public function getMultiple() {
        if (is_null($this->user)) throw new \DomainException("User not logged in.");
        $projects = DB::table('memberships')
            ->where('user_id', $this->user->id)
            ->leftJoin('projects', 'project_id', '=', 'projects.id')
            ->orderBy('projects.created_at', 'desc')
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
            return Status::fromError('Project not found', StatusCodes::NOT_FOUND);
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

    public function deleteMultiple($args) {
        $validator = Validator::make($args, [
            'ids' => 'required|array'
            ]);
        if ($validator->fails()) {
            return Status::fromValidator($validator);
        }

        // TODO: make separate checkPermission function accepting multiple project ids.
        foreach ($args['ids'] as $id) {
            $memberStatus = $this->memberService->checkPermission($id, 'o', $this->user);
            if (!$memberStatus->isOK()) return $memberStatus;
        }
        // Delete all projects and data.
        Project::whereIn('id', $args['ids'])->delete();
        Membership::whereIn('project_id', $args['ids'])->delete();
        Bookmark::whereIn('project_id', $args['ids'])->delete();
        Tag::whereIn('project_id', $args['ids'])->delete();
        Snippet::whereIn('project_id', $args['ids'])->delete();
        return Status::OK();
    }

    public function update($args) {
        $validator = Validator::make($args, [
            'id' => 'required|integer',
            'private' => 'sometimes|boolean'
            ]);
        if ($validator->fails()) {
            return Status::fromValidator($validator);
        }
        $project = Project::find($args['id']);
        if (is_null($project)) {
            return Status::fromError('Project not found', StatusCodes::NOT_FOUND);
        }
        $memberStatus = $this->memberService->checkPermission($project->id, 'w', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;
        if (array_key_exists('private', $args)) {
            $project->private = $args['private'];
        }
        $project->update($args);
        return Status::OK();
    }


    public function getSharedUsers($project_id) {
        $memberStatus = $this->memberService->checkPermission($project_id, 'r', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;
        $memberships = Membership::where('project_id', $project_id)->with('user')->get();

        return Status::fromResult($memberships);
    }

    // User must be logged in.
    public function getSharedProjects() {
        return DB::table('projects')
            ->join('memberships', 'projects.id', '=', 'memberships.project_id')
            ->where('projects.creator_id', '!=', $this->user->id)
            ->where('memberships.user_id', '=', $this->user->id)
            ->select('projects.*', 'memberships.level as level')
            ->get();
    }

    // User must be logged in.
    public function getMyProjects() {
        return Project::where('creator_id', $this->user->id)->get();
    }

    // Shares the project with user with the specified level.
    // If $overwrite is false then it will return error if
    // the user is already being shared on this project.
    public function share($args, $overwrite=false) {
        $validator = Validator::make($args, [
            'id' => 'required|integer',
            'user_id' => 'sometimes|integer|exists:users,id',
            'user_email' => 'required_without:user_id|email',
            'permission' => 'required|string|in:r,w,o'
            ]);
        if ($validator->fails()) {
            return Status::fromValidator($validator);
        }

        $project = Project::find($args['id']);
        if (is_null($project)) {
            return Status::fromError('Project not found', StatusCodes::NOT_FOUND);
        }

        $memberStatus = $this->memberService->checkPermission($project->id, 'o', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;

        $user_id = null;
        if (array_key_exists('user_id', $args)) {
            $user_id = $args['user_id'];
        } else {
            $user = User::where('email', $args['user_email'])->first();
            if (is_null($user)) {
                return Status::fromError('User not found', StatusCodes::NOT_FOUND);
            }
            $user_id = $user->id;
        }

        $membership = Membership::where([
            'user_id' => $user_id,
            'project_id' => $project->id
            ])->first();
        if (!is_null($membership)) {
            if (!$overwrite) {
                return Status::fromError('This user is already a member');
            }
        } else {
            $membership = new Membership();
            $membership->user_id = $user_id;
            $membership->project_id = $project->id;
        }

        $membership->level = $args['permission'];
        $membership->save();

        return Status::OK();
    }

    public function unshare($args) {
        $validator = Validator::make($args, [
            'id' => 'required|integer',
            'user_id' => 'sometimes|integer|exists:users,id',
            'user_email' => 'required_without:user_id|email'
            ]);
        if ($validator->fails()) {
            return Status::fromValidator($validator);
        }

        $project = Project::find($args['id']);
        if (is_null($project)) {
            return Status::fromError('Project not found', StatusCodes::NOT_FOUND);
        }

        $memberStatus = $this->memberService->checkPermission($project->id, 'o', $this->user);
        if (!$memberStatus->isOK()) return $memberStatus;

        $user_id = null;
        if (array_key_exists('user_id', $args)) {
            $user_id = $args['user_id'];
        } else {
            $user = User::where('email', $args['user_email'])->first();
            if (is_null($user)) {
                return Status::fromError('User not found', StatusCodes::NOT_FOUND);
            }
            $user_id = $user->id;
        }

        $membership = Membership::where([
            'user_id' => $user_id,
            'project_id' => $project->id
            ])->first();
        if (is_null($membership)) {
            return Status::fromError('This user is not a member');
        }
        $membership->delete();
        return Status::OK();
    }
}