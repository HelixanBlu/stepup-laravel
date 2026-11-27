<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Redemption extends Model
{
    use HasFactory;

    protected $table = 'redemptions';

    protected $primaryKey = 'redemption_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'reward_id',
        'poin_digunakan',
        'nama_reward',
        'tanggal_redeem',
    ];

    protected $casts = [
        'poin_digunakan' => 'integer',
        'tanggal_redeem' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class, 'reward_id', 'reward_id');
    }

    public function getIdAttribute(): mixed
    {
        return $this->attributes['redemption_id'] ?? null;
    }
}
