<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function unitMeasure(){
        return $this->belongsTo(UnitMeasure::class);
    }
    
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'unit_id',
        'has_alcohol'
    ];
}