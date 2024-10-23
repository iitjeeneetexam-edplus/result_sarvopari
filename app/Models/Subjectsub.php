<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subjectsub extends Model
{
    protected $table = "subject_subs";
    protected $fillable = [
        'subject_id', 'subject_name', 'status',
    ];

}
