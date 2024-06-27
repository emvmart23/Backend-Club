<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function detail(){
        return $this->hasOne(Detail::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'mozo_id');
    }

    protected $fillable = [
        'mozo_id',
        'current_user',
        'box_date'
    ];
}
