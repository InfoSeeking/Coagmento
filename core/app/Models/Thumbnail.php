<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    protected $table = 'thumbnails';
    protected $visible = ['id', 'image_small', 'image_large'];
    protected $fillable = ['image_small', 'image_large', 'url'];
}
