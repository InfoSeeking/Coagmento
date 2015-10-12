<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';
    protected $fillable = ['url', 'title'];
    protected $guarded = ['user_id', 'project_id'];
    // A list of fields present when converted to an array (e.g. for JSON response in API).
    protected $visible = ['url', 'title', 'created_at', 'updated_at', 'user_id', 'project_id', 'id'];
}
