<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{


    protected $table = 'stages';
    protected $visible = ['title', 'page','id','toggle_extension'];
    protected $guarded = ['stage_id'];
    protected $fillable = ['title', 'page','id', 'weight', 'toggle_extension'];

    public $timestamps = false;

    public function widgets(){
        return $this->hasMany('App\Models\Widget');
    }

}
