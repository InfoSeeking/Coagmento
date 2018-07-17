<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';
    protected $visible = ['value', 'link'];
    protected $fillable = ['type', 'value', 'link'];

    public function stage(){
        return $this->belongsTo('App\Models\Stage');
    }


}
