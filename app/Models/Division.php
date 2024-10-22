<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['division_name', 'status', 'standard_id']; // Include standard_id

    public function standard()
    {
        return $this->belongsTo(Standard::class); // Define the relationship with Standard
    }
}
