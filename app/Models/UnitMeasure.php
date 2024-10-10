<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
    use HasFactory;

    protected $primaryKey = 'unit_id';

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function otherExpense()
    {
        return $this->hasMany(OtherExpense::class);
    }

    protected $fillable = [
        'abbreviation',
        'description'
    ];
}
