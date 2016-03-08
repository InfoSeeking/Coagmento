<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldProject extends Model
{
    protected $connection = 'old';
    protected $table = 'projects';
    
    // Required.
    public static function getTableName() {
    	return 'projects';
    }
}
