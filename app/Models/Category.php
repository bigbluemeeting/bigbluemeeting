<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'category';
    protected $fillable = [
        'itemname_eng', 'itemname_mal', 'parent_id', 'has_child', 'has_profilecount'
    ];
}
