<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class V2Notification extends Model
{
	protected $table = "v2_notifications";
	protected $fillable = ["email"];
    protected $visible = ["email", "created_at"];
}
