<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'school_name',
        'school_index',
        'address',
        'email',
        'contact_no',
        'status',
    ];
}
