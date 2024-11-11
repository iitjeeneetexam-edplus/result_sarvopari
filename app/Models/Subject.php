<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;
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
