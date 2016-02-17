<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $fillable = ['text', 'url', 'title'];
    protected $guarded = ['user_id', 'project_id'];
    protected $visible = ['thumbnail', 'text', 'url', 'title', 'created_at', 'updated_at', 'user_id', 'project_id', 'id'];
    public function thumbnail() {
    	return $this->hasOne('App\Models\Thumbnail', 'id', 'thumbnail_id');
    }
}
