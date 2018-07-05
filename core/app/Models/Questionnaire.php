<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = ['title', 'data', 'questions'];
    protected $casts = [
        'data' => 'array',
    ];

    public function questions(){
        return $this->hasMany('App\Models\Question');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
