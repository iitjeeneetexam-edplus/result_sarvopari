<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['exam_name', 'subject_id', 'date','total_marks']; // Add total_marks

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
