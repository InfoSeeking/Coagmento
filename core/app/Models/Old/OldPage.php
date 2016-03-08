<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldPage extends Model
{
    protected $connection = 'old';
    protected $table = 'pages';

    // Required.
    public static function getTableName() {
    	return 'pages';
    }
}
