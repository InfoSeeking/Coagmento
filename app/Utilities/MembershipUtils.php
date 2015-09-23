<?php
namespace App\Utilities;

use App\Models\Membership;

class MembershipUtils {
	public static function permissionToString($level) {
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

 	public static function checkPermission($userId, $projectId, $level) {
    	$rows = Membership::where('user_id', $userId)->where('project_id', $projectId)->get();
    	if ($rows->count() == 0) {
    		return Status::fromError('You do not have access to this project.');
    	}
    	$current = $rows->first()['level'];
    	if ($level == 'r') {
    		return Status::OK();
    	} else if ($level == 'w' && ($current == 'w' || $current == 'o')) {
    		return Status::OK();
    	} else if ($level == 'o' && $current == 'o') {
    		return Status::OK();
    	}

    	$msg = sprintf('You need %s permission to access this project, but you only have %s permission.',
    		self::permissionToString($level),
    		self::permissionToString($current));

    	return Status::fromError($msg);
    }
}