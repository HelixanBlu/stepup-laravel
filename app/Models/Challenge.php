<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    use HasFactory;

    protected $table = 'challenges';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'description',
        'banner_image',
        'challenge_type',
        'goal_type',
        'goal_value',
        'reward_points',
        'badge_reward',
        'participant_count',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'goal_value' => 'integer',
        'reward_points' => 'integer',
        'participant_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_challenges', 'challenge_id', 'collection_id')->withTimestamps();
    }

    public function badges(): HasMany
    {
        return $this->hasMany(Badge::class, 'related_challenge_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class, 'challenge_id');
    }

    public function completedParticipants(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class, 'challenge_id')
            ->where('completion_status', 'completed');
    }
}
