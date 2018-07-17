<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuerySegment extends Model
{
    //

    protected $fillable = ['user_id','query_id','query_segment_id','query_useful','query_barriers','relevant_helps'];
}
