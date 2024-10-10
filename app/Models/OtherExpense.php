<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherExpense extends Model
{
    use HasFactory;

    public function unit()
    {
        return $this->belongsTo(UnitMeasure::class, 'unit_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'current_user');
    }

    protected $fillable = [
        'count',
        'name',
        'unit_id',
        'total',
        "current_user",
        "box_date"
    ];
}
