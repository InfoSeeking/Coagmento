<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    //
    protected $fillable = ['action', 'value'];
    protected $guarded = ['project_id', 'user_id', 'stage_id'];
    protected $visible = ['created_at', 'updated_at', 'stage_id', 'user_id', 'project_id', 'id', 'action', 'value', 'created_at_local','created_at_local_ms'];
}
