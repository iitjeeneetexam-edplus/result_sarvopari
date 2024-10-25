<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['exam_name', 'standard_id', 'date']; // Add total_marks

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
