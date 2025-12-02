<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'token_number',
        'requestid',
        'nps_score',
        'main_options',
        'sub_options',
        'comment',
        'remark',
        'status',
        'case_status',
    ];

    protected $casts = [
        'main_options' => 'array',
        'sub_options'  => 'array',
    ]; 
}
