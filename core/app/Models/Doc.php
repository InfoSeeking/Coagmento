<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    protected $table = 'docs';
    protected $visible = ['id', 'created_at', 'updated_at', 'title', 'project_id', 'creator_id'];
    protected $fillable = ['title'];
    protected $guarded = ['etherpad_group_id'];

    public function getPadId() {
    	return $this->etherpad_group_id . '$' . $this->id;
    }
}
