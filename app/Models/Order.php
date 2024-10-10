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

    public function user()
    {
        return $this->belongsTo(User::class, 'hostess_id', 'id');
    }

    public  function  product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $fillable = [
        'hostess_id',
        'product_id',
        'price',
        'count',
        'total_price',
        'header_id',
        "current_user",
        "box_date"
    ];
}
