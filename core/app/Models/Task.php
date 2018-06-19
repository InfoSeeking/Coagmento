<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['description'];
//    protected $guarded = ['task_id', 'project_id'];
    protected $visible = ['id','description', 'created_at', 'updated_at'];

    public function attributes(){
        return $this->belongsToMany('App\Models\Attribute', 'task_attribute_assignments', 'task_id', 'attribute_id')
        /*->withTimestamps()*/;
    }


}
