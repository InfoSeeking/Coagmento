<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldMembership extends Model
{
    protected $connection = 'old';
    protected $table = 'memberships';
    
    // Required.
    public static function getTableName() {
    	return 'memberships';
    }
}
