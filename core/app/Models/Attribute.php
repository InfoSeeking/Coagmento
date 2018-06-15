<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $visible=['task_id', 'name', 'type'];
    protected $fillable=['task_id','name', 'type', 'option_name'];

    protected $casts = [
        'option_name' => 'array',
    ];
}
