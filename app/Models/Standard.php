<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standard extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['school_id', 'standard_name', 'status'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
