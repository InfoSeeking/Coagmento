<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demographic extends Model
{
    //

    protected $fillable = ['age','gender','major','english_first','native_language','search_experience','search_frequency','nonsearch_frequency','consent_datacollection','consent_audio','consent_furtheruse'];
    protected $guarded = ['user_id'];
}
