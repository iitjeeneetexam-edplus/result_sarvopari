<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marks extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'marks';

    protected $fillable = [
        'student_id',
        'subject_id',
        'exam_id',
        'is_optional',
        'total_marks',
        'passing_marks',
        'marks',
    ];
}
