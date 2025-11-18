<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    protected $table = 'customers';   // if table name is customers
    protected $fillable =[
    'name',
    'policy_number',
    'aadhaar',
    'phone',
    'address'];  
}