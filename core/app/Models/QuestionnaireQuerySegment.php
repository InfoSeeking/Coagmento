<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuerySegment extends Model
{
    //

    protected $fillable = ['user_id','query_id','query_segment_id','useful','barriers','help'];
}
