<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldUser extends Model
{
    protected $connection = 'old';
    protected $table = 'users';
    
    // Required.
    public static function getTableName() {
    	return 'users';
    }
}
