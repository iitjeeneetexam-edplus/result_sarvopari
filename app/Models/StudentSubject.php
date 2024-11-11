<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSubject extends Model
{
    use SoftDeletes;
    protected $table = 'student_subjects';
    protected $fillable = ['id',
        'student_id',
        'subject_id',
    ];
}
