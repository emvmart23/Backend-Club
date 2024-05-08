<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    public function attendance() { 
        return $this->hasMany(Attendance::class);
    }

    protected $fillable = [
        "opening",
        "closing",
        "initial_balance",
        "final_balance",
        "state"
    ];
}