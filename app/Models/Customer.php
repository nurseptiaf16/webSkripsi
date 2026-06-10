<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Olt;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'olt_id',
        'month',
        'year',
        'b2c',
        'b2b',
        'total_customers'
    ];

    // 🔥 RELASI KE OLT
    public function olt()
    {
        return $this->belongsTo(Olt::class);
    }
}