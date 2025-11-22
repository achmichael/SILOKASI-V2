<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function ratings()
    {
        return $this->hasMany(AlternativeRating::class);
    }

    public function bordaPoints()
    {
        return $this->hasMany(BordaPoint::class);
    }
}
