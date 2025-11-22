<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
    ];

    public function ratings()
    {
        return $this->hasMany(AlternativeRating::class);
    }

    public function pairwiseComparisonsI()
    {
        return $this->hasMany(PairwiseComparison::class, 'criteria_i');
    }

    public function pairwiseComparisonsJ()
    {
        return $this->hasMany(PairwiseComparison::class, 'criteria_j');
    }

    public function anpInterdependenciesI()
    {
        return $this->hasMany(AnpInterdependency::class, 'criteria_i');
    }

    public function anpInterdependenciesJ()
    {
        return $this->hasMany(AnpInterdependency::class, 'criteria_j');
    }
}
