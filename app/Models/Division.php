<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "division";
    protected $fillable = ['division_name', 'status', 'standard_id']; // Include standard_id

    public function standard()
    {
        return $this->belongsTo(Standard::class); 
    }
}
