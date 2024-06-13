<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function detail()
    {
        return $this->belongsTo(Detail::class);
    }

    protected $fillable = ["detail_id", "payment_method", "mountain", "reference"];
}
