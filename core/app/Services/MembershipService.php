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

 	public function checkPermission($project_id, $level, $user=null) {
        // Public projects give read permission to any user.
        if ($user == null) {
            // Check if this is a public project.
            $project = Project::find($project_id);
            if (!$project->private) {
                return Status::fromResult('r');
            } else {
                return Status::fromError('You must be logged in to view this project');    
            }
        }

    	$rows = Membership::where('user_id', $user->id)->where('project_id', $project_id)->get();
    	if ($rows->count() == 0) {
    		return Status::fromError('You do not have access to this project.');
    	}
    	$current = $rows->first()['level'];
    	if ($level == 'r') {
    		return Status::fromResult($current);
    	} else if ($level == 'w' && ($current == 'w' || $current == 'o')) {
    		return Status::fromResult($current);
    	} else if ($level == 'o' && $current == 'o') {
    		return Status::fromResult($current);
    	}

    	$msg = sprintf('You need %s permission to access this project, but you only have %s permission.',
    		self::permissionToString($level),
    		self::permissionToString($current));

    	return Status::fromError($msg);
    }
}