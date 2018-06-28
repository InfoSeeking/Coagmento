<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnairePretask extends Model
{
    //
    protected $fillable = ['search_difficulty','information_understanding',
        'decide_usefulness','information_integration','information_sufficient',
        'topic_prev_knowledge',
        'goal_specific',
        'task_pre_difficulty',
        'narrow_information',
        'task_newinformation',
        'task_unspecified',
        'task_detail',
        'task_knowspecific',
        'task_specificitems',
        'task_interest',
        'task_factors',
        'queries_start',
        'know_usefulinfo',
        'useful_notobtain'];

    protected $guarded = ['user_id','stage_id'];
}
