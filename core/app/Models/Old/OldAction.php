<?php
namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

class OldAction extends Model
{
    protected $connection = 'old';
    protected $table = 'actions';

    // Required.
    public static function getTableName() {
    	return 'actions';
    }
}
