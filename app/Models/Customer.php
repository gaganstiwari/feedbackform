<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'callers';

    protected $fillable = [
        'requestid',
        'caller_name',
        'caller_number',
        'id_number',
        'place'
    ];
}
