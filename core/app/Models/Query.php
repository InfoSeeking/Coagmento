<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
	// The url is not saved with the query since a search query
	// could be performed outside of a web context.
    protected $table = 'queries';
    protected $fillable = ['text', 'search_engine'];
    protected $guarded = ['project_id', 'user_id'];
    protected $visible = ['text', 'search_engine', 'created_at', 'updated_at', 'user_id', 'project_id', 'id'];
}
