<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookmarksAndTags extends Model
{
    protected $table = 'bookmarks_and_tags';
    protected $guarded = ['bookmark_id', 'tag_id'];
}
