<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnairePretask extends Model
{
    //
    protected $fillable = ['user_id','stage_id','search_difficulty','information_understanding',
        'decide_usefulness','information_integration','information_sufficient'];
}
