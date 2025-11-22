<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnpInterdependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'criteria_i',
        'criteria_j',
        'value',
    ];

    public function criteriaI()
    {
        return $this->belongsTo(Criteria::class, 'criteria_i');
    }

    public function criteriaJ()
    {
        return $this->belongsTo(Criteria::class, 'criteria_j');
    }
}
