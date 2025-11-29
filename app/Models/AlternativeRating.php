<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternativeRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'alternative_id',
        'criteria_id',
        'rating',
        'user_id',
    ];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
