<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['description', 'product', 'goal'];
//    protected $guarded = ['task_id', 'project_id'];
    protected $visible = ['id','description', 'product', 'goal', 'created_at', 'updated_at'];

    public function attributes(){
        $this->hasMany('App\Models\Attribute');
    }

}
