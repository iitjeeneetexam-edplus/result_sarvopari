<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subjectsub extends Model
{
    use SoftDeletes;
    protected $table = "subject_subs";
    protected $fillable = [
        'subject_id', 'subject_name', 'status',
    ];

}
