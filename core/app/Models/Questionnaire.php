<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    public function question(){
        return $this->hasMany('App\Models\Question');
    }
}
