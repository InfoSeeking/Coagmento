<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	protected $table = 'tags';
    protected $fillable = ['name'];
    protected $guarded = ['creator_id', 'project_id'];
    protected $visible = ['name', 'project_id', 'creator_id', 'created_at', 'updated_at'];
}
