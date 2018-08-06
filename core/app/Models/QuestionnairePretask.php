<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnairePretask extends Model
{
    //
    protected $fillable = [

        'goal_specific',
        'task_pre_difficulty',
        'narrow_information',
        'task_newinformation',
        'task_unspecified',
        'task_detail',
        'task_knowspecific',

        'queries_start',
        'know_usefulinfo',
        'useful_notobtain','user_id','stage_id',
        'help',
        'task_familiarity',
        'task_effort'
        ];

//    protected $guarded = ['user_id','stage_id'];
}
