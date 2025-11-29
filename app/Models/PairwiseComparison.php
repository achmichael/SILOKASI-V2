<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairwiseComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'criteria_i',
        'criteria_j',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function criteriaI()
    {
        return $this->belongsTo(Criteria::class, 'criteria_i');
    }

    public function criteriaJ()
    {
        return $this->belongsTo(Criteria::class, 'criteria_j');
    }
}
