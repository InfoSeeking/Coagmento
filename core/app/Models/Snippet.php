<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $fillable = ['text', 'url'];
    protected $guarded = ['user_id', 'project_id'];
    protected $visible = ['text', 'url', 'created_at', 'updated_at', 'user_id', 'project_id', 'id'];
}
