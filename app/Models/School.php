<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;
    protected $table = 'schools';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'school_name',
        'school_index',
        'address',
        'email',
        'contact_no',
        'status',
        'user_id'
    ];
}
