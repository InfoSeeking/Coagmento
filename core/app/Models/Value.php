<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = ['label', 'value', 'selected'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function question() {
        return $this->belongsTo(Question::class);
    }

}
