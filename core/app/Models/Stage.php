<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';
    protected $visible = ['title', 'page'];
    protected $guarded = ['stage_id'];
    protected $fillable = [];
}
