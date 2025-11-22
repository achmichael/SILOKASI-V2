<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BordaPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'decision_maker_id',
        'alternative_id',
        'points',
    ];

    public function decisionMaker()
    {
        return $this->belongsTo(DecisionMaker::class);
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}
