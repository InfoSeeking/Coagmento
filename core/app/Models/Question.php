<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function questionnaire(){
        return $this->belongsTo('App\Models\Questionnaire');
    }
}
