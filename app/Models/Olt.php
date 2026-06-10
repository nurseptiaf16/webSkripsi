<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Olt extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'location',
        'status'
    ];

    public function customers()
    {
    return $this->hasMany(Customer::class);
    }
}