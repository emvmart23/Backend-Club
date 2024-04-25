<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo("App\Category");
    }

    public function unitMeasure(){
        return $this->belongsTo("App\UnitMeasure");
    }
    protected $fillable = [
        'name',
        'category_id',
        'unit_id',
        'has_alcohol'
    ];
}