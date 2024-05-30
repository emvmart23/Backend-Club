<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function header()
    {
        return $this->belongsTo(Header::class);
    }

    protected $fillable = [
        'hostess',
        'name',
        'price',
        'count',
        'total_price',
        'header_id'
    ];
}
