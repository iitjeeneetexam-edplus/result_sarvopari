<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['exam_name','exam_year', 'standard_id', 'date','result_date']; // Add total_marks

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
