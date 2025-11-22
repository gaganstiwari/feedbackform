<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'requestid',
        'nps_score',
        'main_options',
        'sub_options',
        'comment',
        'status'
    ];

    protected $casts = [
        'main_options' => 'array',
        'sub_options'  => 'array',
    ]; 
}
