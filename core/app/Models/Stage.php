<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';
    protected $visible = ['stage_id', 'title', 'page'];
    protected $fillable = [];
}
