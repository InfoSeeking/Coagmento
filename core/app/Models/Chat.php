<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat_messages';
    protected $visible = ['message', 'created_at', 'updated_at', 'user_id', 'project_id'];
    protected $fillable = ['message'];
}
