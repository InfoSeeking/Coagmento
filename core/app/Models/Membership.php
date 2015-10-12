<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
	protected $table = 'memberships';
    protected $fillable = ['user_id', 'project_id'];
}
