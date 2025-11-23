<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionMaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'weight',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bordaPoints()
    {
        return $this->hasMany(BordaPoint::class);
    }

    public function alternativeRatings()
    {
        return $this->hasMany(AlternativeRating::class);
    }
}
