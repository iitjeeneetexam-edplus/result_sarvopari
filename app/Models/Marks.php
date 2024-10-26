<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{
    protected $table = 'marks';

    protected $fillable = [
        'student_id',
        'subject_id',
        'exam_id',
        'is_optional',
        'total_marks',
        'marks',
    ];
}
