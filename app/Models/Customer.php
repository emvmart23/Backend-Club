<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function detail(){
        return $this->hasMany(Detail::class);
    }
    
    protected $fillable = [
        'name',
        'dni'
    ];
}
