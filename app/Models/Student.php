<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'roll_no', 'GR_no','uid', 'division_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
