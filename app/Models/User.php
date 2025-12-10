<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Scope to filter decision makers
     */
    public function scopeDecisionMakers($query)
    {
        return $query->whereIn('role', ['land_geotech', 'infrastructure', 'manager']);
    }

    /**
     * Check if user is a decision maker
     */
    public function isDecisionMaker()
    {
        return $this->role === 'land_geotech' || $this->role === 'infrastructure' || $this->role === 'manager';
    }

    /**
     * Get alternative ratings for this user
     */
    public function alternativeRatings()
    {
        return $this->hasMany(AlternativeRating::class);
    }

    /**
     * Get borda points for this user
     */
    public function bordaPoints()
    {
        return $this->hasMany(BordaPoint::class);
    }

    /**
     * Get pairwise comparisons for this user
     */
    public function pairwiseComparisons()
    {
        return $this->hasMany(PairwiseComparison::class);
    }

    public function calculationResults()
    {
        return $this->hasMany(CalculationResult::class);
    }

    /**
     * Get ANP interdependencies for this user
     */
    public function anpInterdependencies()
    {
        return $this->hasMany(AnpInterdependency::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
