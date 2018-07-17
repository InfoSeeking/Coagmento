<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{


    protected $table = 'stages';
    protected $visible = ['title', 'page','id'];
    protected $guarded = ['stage_id'];
    protected $fillable = ['title', 'page','id', 'weight'];

    public $timestamps = false;

    public function widgets(){
        return $this->hasMany('App\Models\Widget');
    }

}
