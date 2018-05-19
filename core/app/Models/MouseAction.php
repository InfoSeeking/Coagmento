<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouseAction extends Model
{
    protected $fillable = ['type', 'client_x', 'client_y', 'page_x', 'page_y', 'screen_x', 'screen_y', 'scroll_x', 'scroll_y', 'created_at_local','created_at_local_ms'];
    protected $guarded = ['project_id', 'user_id', 'stage_id'];
    protected $visible = ['created_at', 'updated_at', 'user_id', 'project_id', 'id', 'type', 'client_x', 'client_y', 'page_x', 'page_y', 'screen_x', 'screen_y', 'scroll_x', 'scroll_y', 'type', 'created_at_local','created_at_local_ms'];
}
