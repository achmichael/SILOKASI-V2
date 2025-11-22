<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionMaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weight',
    ];

    public function bordaPoints()
    {
        return $this->hasMany(BordaPoint::class);
    }
}
