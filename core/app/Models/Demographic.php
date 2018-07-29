<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demographic extends Model
{
    //

    protected $fillable = ['user_id','age','gender','major','english_first','native_language','search_experience','search_frequency','nonsearch_frequency','consent_datacollection','consent_audio','consent_furtheruse','study_date'];
//    protected $guarded = ['user_id'];
}
