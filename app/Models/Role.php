<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    public function users()
    {
        return $this->hasMany("App\User");
    }

    protected $fillable = [
        'role_name'
    ];
}