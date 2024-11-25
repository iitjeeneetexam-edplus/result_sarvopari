<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siddhigun extends Model
{
    protected $table = 'krupa_sidhi_gun';
    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_id',
        'sidhi_gun',
    ];
}
