<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldBookmark extends Model
{
    protected $connection = 'old';
    protected $table = 'bookmarks';

    // Required.
    public static function getTableName() {
    	return 'bookmarks';
    }
}
