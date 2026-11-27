<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'nama',
        'role',
        'status',
        'poin',
        'foto_profile',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'poin' => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function challengeParticipants(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class, 'user_id');
    }

    public function completedChallenges(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class, 'user_id')
            ->where('completion_status', 'completed');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class, 'user_id');
    }

    public function communityPosts(): HasMany
    {
        return $this->hasMany(Community::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function getIdAttribute(): mixed
    {
        return $this->attributes['user_id'] ?? null;
    }
}
