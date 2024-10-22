<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = "subjects";
    protected $fillable = [
        'subject_name',
        'is_optional',
        'status',
        'standard_id', // Add this line
    ];

    // Define the relationship with the Standard model
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
