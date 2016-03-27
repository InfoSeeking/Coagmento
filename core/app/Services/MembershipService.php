<?php
namespace App\Services;

use App\Models\Membership;
use App\Models\Project;
use App\Utilities\Status;

class MembershipService {
    public function permissionToString($level) {
        switch ($level) {
            case 'w':
            return 'write';
            case 'r':
            return 'read';
            case 'o':
            return 'owner';
            default:
            return 'non-existant';
        }
    }

    // Simpler function returning a boolean.
    public function can($project_id, $level, $user=null) {
        $memberStatus = $this->checkPermission($project_id, $level, $user);
        return $memberStatus->isOK();
    }

    // Checks if the user has a membership permission satisfiying $level.
    // own   - has all permissions
    // write - has write/read permissions
    // read  - has read permissions
    // none  - has read permissions on a public project
    // Returns a status with the existing highest level of permission the user has.
    public function checkPermission($project_id, $level, $user=null) {
        if ($user == null) {
            return $this->checkPermissionWithoutMembership($level, $project_id);
        }

        // The user is allowed to pass the full word for convenient notation.
        if ($level == 'read') $level = 'r';
        else if ($level == 'write') $level = 'w';
        else if ($level == 'own') $level = 'o';

        $membership = Membership::where('user_id', $user->id)->where('project_id', $project_id)->first();
        if (is_null($membership)) {
            return $this->checkPermissionWithoutMembership($level, $project_id);
        }

        $current = $membership['level'];
        if ($level == 'r') {
            return Status::fromResult($current);
        } else if ($level == 'w' && ($current == 'w' || $current == 'o')) {
            return Status::fromResult($current);
        } else if ($level == 'o' && $current == 'o') {
            return Status::fromResult($current);
        }  

        $msg = sprintf('You need %s permission to access this project, but you only have %s permission.',
            $this->permissionToString($level),
            $this->permissionToString($current));

        return Status::fromError($msg);
    }

    protected function checkPermissionWithoutMembership($level, $project_id) {
        // If the user requests anything other than read permission, deny it.
        if ($level != 'r') {
            return Status::fromError('You must be logged in as a project member to access');
        }
        $project = Project::find($project_id);
        if ($project->private) {
            return Status::fromError('You must be logged in as a project member to access');
        } else {
            return Status::OK();
        }
    }
}