<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance_grace_Model extends Model
{
    protected $table = 'performance_grace';

    protected $fillable = [
        'school_id','performance', 'grace'
    ];
}
