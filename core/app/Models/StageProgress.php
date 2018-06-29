<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StageProgress extends Model
{
    protected $table = 'stages_progress';
    protected $visible = ['id','project_id','user_id','stage_id','date','time','timestamp','local_date','local_time','local_timestamp'];
    protected $fillable = ['user_id','stage_id'];
//    protected $guarded = ['user_id'];
}
