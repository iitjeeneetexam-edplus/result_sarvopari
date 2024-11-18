<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['exam_name','is_practical','exam_year', 'standard_id', 'date','result_date']; // Add total_marks

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
