<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['url', 'title'];
    protected $guarded = ['project_id', 'user_id'];
    protected $visible = ['thumbnail', 'url', 'title', 'created_at', 'updated_at', 'user_id', 'project_id', 'id', 'is_query'];
    public function thumbnail() {
    	return $this->hasOne('App\Models\Thumbnail', 'id', 'thumbnail_id');
    }
}
