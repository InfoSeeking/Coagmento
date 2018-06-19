<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $visible=['task_id', 'name', 'type'];
    protected $fillable=['id','task_id','name', 'type', 'option_name'];

    protected $casts = [
        'option_name' => 'array',
    ];

    protected $appends=['option_name'];

    public function tasks(){
        return $this->belongsToMany('App\Models\Task', 'task_attribute_assignments', 'attribute_id', 'task_id');
    }
}
