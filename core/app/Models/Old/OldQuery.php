<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldQuery extends Model
{
    protected $connection = 'old';
    protected $table = 'queries';

    // Required.
    public static function getTableName() {
    	return 'queries';
    }
}
