<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class blog extends Model
{
    protected $table = "blogs";
    protected $fillable = [
        'title',
        'discription',
    ];
}
