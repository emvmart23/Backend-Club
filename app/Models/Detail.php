<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function header(){
        return $this->belongsTo(Header::class, 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'current_user'); 
    }

    protected $fillable = [
        "client_id",
        "issue_date",
        "total_price",
        "current_user",
        "box_date"
    ];
}
