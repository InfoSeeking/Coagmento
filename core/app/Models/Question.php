<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable=['type','label', 'name', 'subType', 'style', 'className', 'values', 'required', 'inline', 'description'];

    public function questionnaire(){
        return $this->belongsTo('App\Models\Questionnaire');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
    public function values() {
        return $this->hasMany(Value::class);
    }

}
