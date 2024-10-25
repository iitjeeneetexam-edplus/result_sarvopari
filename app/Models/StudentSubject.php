<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    protected $table = 'student_subjects';
    protected $fillable = ['id',
        'student_id',
        'subject_id',
    ];
}
