<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasteAction extends Model
{
    protected $fillable = ['url', 'title', 'snippet'];
    protected $guarded = ['project_id', 'user_id', 'stage_id'];
    protected $visible = ['created_at', 'updated_at', 'user_id', 'project_id', 'id', 'url', 'title', 'snippet', 'created_at_local','created_at_local_ms'];
}
