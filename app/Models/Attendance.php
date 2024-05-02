<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo("App\User");
    }

    protected $fillable = [
        'user_id',
        'present',
        'absent',
        'late',
        'date'
    ];

}