<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keystroke extends Model
{
    protected $fillable = ['key_code','modifiers'];
    protected $guarded = ['project_id', 'user_id', 'stage_id'];
    protected $visible = ['created_at', 'updated_at', 'user_id', 'project_id', 'id', 'key_code', 'modifiers', 'created_at_local','created_at_local_ms'];
}
