<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable=['from', 'to', 'cc', 'bcc', 'subject', 'body'];
}