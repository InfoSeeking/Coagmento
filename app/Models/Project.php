<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	protected $table = 'projects';
    protected $fillable = ['title', 'description'];
    protected $guarded = ['creator_id'];
    protected $visisble = ['title', 'description', 'created_at', 'updated_at', 'id'];
}
