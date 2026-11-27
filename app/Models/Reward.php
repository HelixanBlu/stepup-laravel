<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards';

    protected $primaryKey = 'reward_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'name_reward',
        'description',
        'poin',
        'gambar',
        'stok',
    ];

    protected $casts = [
        'poin' => 'integer',
        'stok' => 'integer',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class, 'reward_id', 'reward_id');
    }

    public function getIdAttribute(): mixed
    {
        return $this->attributes['reward_id'] ?? null;
    }

    public function getIdRewardAttribute(): mixed
    {
        return $this->attributes['reward_id'] ?? null;
    }
}
