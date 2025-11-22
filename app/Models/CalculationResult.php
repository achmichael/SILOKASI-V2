<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'data',
        'calculated_at',
    ];

    protected $casts = [
        'data' => 'array',
        'calculated_at' => 'datetime',
    ];
}
