<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAttributeAssignment extends Model
{
    protected $fillable=['value'];
    protected $visible=['task_id', 'attribute_id','value'];
}
