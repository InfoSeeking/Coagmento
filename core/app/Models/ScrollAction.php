<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrollAction extends Model
{
    protected $fillable = ['type', 'screen_x', 'screen_y', 'scroll_x', 'scroll_y'];
    protected $guarded = ['project_id', 'user_id', 'stage_id'];
    protected $visible = ['created_at', 'updated_at', 'user_id', 'project_id', 'id', 'type', 'screen_x', 'screen_y', 'scroll_x', 'scroll_y', 'created_at_local','created_at_local_ms'];
}
