<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';
    protected $fillable = ['url', 'title', 'notes'];
    protected $guarded = ['user_id', 'project_id'];
    // A list of fields present when converted to an array (e.g. for JSON response in API).
    protected $visible = ['thumbnail', 'url', 'title', 'created_at', 'updated_at', 'user_id', 'project_id', 'id', 'notes'];

    public function thumbnail() {
    	return $this->hasOne('App\Models\Thumbnail', 'id', 'thumbnail_id');
    }
}
