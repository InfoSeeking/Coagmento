<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnairePosttask extends Model
{

    //
    protected $fillable = ['user_id','stage_id','satisfaction',
        'system_helpfulness','goal_success','mental_demand','physical_demand','temporal_demand',
        'effort','frustration','difficulty','task_success','enough_time'];
}
